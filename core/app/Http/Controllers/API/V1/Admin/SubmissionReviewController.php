<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\RejectSubmissionRequest;
use App\Http\Resources\V1\Sale\SubmissionResource;
use App\Models\ThoSubmission;
use App\Services\SubmissionReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API V1 — Admin. Quản lý duyệt/từ chối hồ sơ thợ (Phase 2).
 * Ref: DUC-SUBMISSION-APPROVE, DUC-SUBMISSION-REJECT.
 */
class SubmissionReviewController extends Controller
{
    public function __construct(private readonly SubmissionReviewService $service)
    {
    }

    /**
     * Chỉ Quản lý (user_id trong config sale.manager_user_ids) được duyệt.
     * Rỗng = khoá hết (an toàn mặc định). TODO Phase 3: admin guard chuẩn.
     */
    private function assertManager(): void
    {
        $ids = config('sale.manager_user_ids', []);
        if (empty($ids) || ! in_array((int) auth()->id(), $ids, true)) {
            abort(403, 'Bạn không có quyền duyệt hồ sơ.');
        }
    }

    public function index(Request $request): JsonResponse
    {
        $this->assertManager();
        $status  = $request->query('status', 'pending');
        $perPage = max(1, min(50, (int) $request->integer('per_page', 20)));

        // with('images'): Quản lý phải NHÌN được ảnh mới nghiệm thu (BR-2) —
        // trước đây chỉ trả số lượng ảnh, màn duyệt bị "duyệt mù".
        $page = ThoSubmission::where('status', $status)
            ->with('images')
            ->withCount('images')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'data' => SubmissionResource::collection($page->items()),
            'meta' => [
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
            ],
        ]);
    }

    public function approve(int $id): JsonResponse
    {
        $this->assertManager();
        $submission = ThoSubmission::findOrFail($id);
        $updated = $this->service->approve($submission);

        return (new SubmissionResource($updated->loadCount('images')))->response();
    }

    public function reject(RejectSubmissionRequest $request, int $id): JsonResponse
    {
        $this->assertManager();
        $submission = ThoSubmission::findOrFail($id);
        $updated = $this->service->reject($submission, $request->validated()['ly_do']);

        return (new SubmissionResource($updated->loadCount('images')))->response();
    }

    /**
     * P0.3 — Hàng đợi vận hành: những thứ đang chờ con người xử lý.
     * Một màn hình cho Quản lý trực thay vì rơi vào hư không.
     */
    public function queues(): JsonResponse
    {
        $this->assertManager();

        $staleRequests = \App\Models\ServiceRequest::where('status', 'open')
            ->where('created_at', '<', now()->subDay())
            ->orderBy('created_at')
            ->limit(20)
            ->get(['id', 'title', 'city', 'district', 'contact_name', 'contact_phone', 'created_at']);

        $pendingAppointments = \App\Models\Appointment::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(4))
            ->with('company:id,name,phone')
            ->orderBy('created_at')
            ->limit(20)
            ->get(['id', 'company_id', 'recipient_name', 'appointment_date', 'appointment_time', 'created_at']);

        $pendingDeposits = \App\Models\DepositRequest::where('status', 'pending')
            ->orderBy('created_at')
            ->limit(20)
            ->get(['id', 'amount', 'created_at']);

        $pendingCompanies = \App\Models\Company::where('status', \App\Constants\Status::PENDING)
            ->with('user:id,name,email,mobile')
            ->orderBy('created_at')
            ->limit(20)
            ->get(['id', 'user_id', 'name', 'phone', 'city', 'district', 'category_id', 'created_at']);

        return response()->json([
            'counts' => [
                'stale_requests'       => \App\Models\ServiceRequest::where('status', 'open')->where('created_at', '<', now()->subDay())->count(),
                'pending_appointments' => \App\Models\Appointment::where('status', 'pending')->where('created_at', '<', now()->subHours(4))->count(),
                'pending_deposits'     => \App\Models\DepositRequest::where('status', 'pending')->count(),
                'pending_companies'    => \App\Models\Company::where('status', \App\Constants\Status::PENDING)->count(),
            ],
            'stale_requests'       => $staleRequests,
            'pending_appointments' => $pendingAppointments,
            'pending_deposits'     => $pendingDeposits,
            'pending_companies'    => $pendingCompanies,
        ]);
    }

    /**
     * P1.3 — Hợp nhất duyệt: Quản lý duyệt luôn thợ TỰ ĐĂNG KÝ (company PENDING)
     * ngay trên màn /sale/duyet, không phải vào admin legacy.
     * Duyệt = APPROVED + ví + tín dụng chào mừng + notify (idempotent).
     */
    public function approveCompany(int $id): JsonResponse
    {
        $this->assertManager();

        $company = \App\Models\Company::with('user')->findOrFail($id);
        if ((int) $company->status !== \App\Constants\Status::PENDING) {
            abort(422, 'Hồ sơ không ở trạng thái chờ duyệt.');
        }

        $company->status = \App\Constants\Status::APPROVED;
        $company->save();

        $wallet = \App\Models\CompanyWallet::createForCompany($company);
        if ($wallet->wasRecentlyCreated && $company->user) {
            $credit = number_format((int) config('marketplace.welcome_credit', 200000), 0, ',', '.');
            \App\Services\NotificationService::sendSystemNotification(
                $company->user,
                'Hồ sơ thợ đã được duyệt 🎉',
                "Chúc mừng! Hồ sơ \"{$company->name}\" đã lên chợ. Doitay tặng bạn {$credit}đ vào ví để nhận những khách đầu tiên.",
                'company_approved',
                url('/vi/tho/lich-hen'),
            );
        }

        return response()->json(['data' => ['id' => $company->id, 'status' => 'approved']]);
    }
}
