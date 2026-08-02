<?php

namespace App\Http\Controllers\API\V1\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Sale\CreateSubmissionRequest;
use App\Http\Resources\V1\Sale\SubmissionResource;
use App\Models\ThoSubmission;
use App\Services\SubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API V1 — Sale namespace. CTV nhập & xem hồ sơ thợ (Phase 1).
 * Ref: DUC-SUBMISSION-CREATE, DUC-SUBMISSION-LIST.
 */
class SubmissionController extends Controller
{
    public function __construct(private readonly SubmissionService $service)
    {
    }

    public function store(CreateSubmissionRequest $request): JsonResponse
    {
        $submission = $this->service->create(
            $request->user(),
            $request->validated(),
            $request->file('images', []),
        );

        return (new SubmissionResource($submission))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /sale/tho-lookup?sdt=... — CTV gõ SĐT trước khi nộp:
     * số này thợ đã tự mở hồ sơ chưa, đã có CTV nhận công chưa.
     */
    public function lookup(Request $request): JsonResponse
    {
        $sdt = trim((string) $request->query('sdt', ''));
        if ($sdt === '') {
            return response()->json(['message' => 'Thiếu số điện thoại'], 422);
        }

        return response()->json(['data' => $this->service->lookupByPhone($sdt)]);
    }

    public function index(Request $request): JsonResponse
    {
        $userId  = $request->user()->id;
        $perPage = max(1, min(50, (int) $request->integer('per_page', 20)));

        $query = ThoSubmission::where('ctv_id', $userId)
            ->with('images')
            ->withCount('images')
            ->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $page = $query->paginate($perPage);

        // Thống kê cho dashboard CTV: đếm theo trạng thái + tổng hoa hồng.
        $byStatus = ThoSubmission::where('ctv_id', $userId)
            ->selectRaw('status, count(*) as n')
            ->groupBy('status')
            ->pluck('n', 'status');

        return response()->json([
            'data' => SubmissionResource::collection($page->items()),
            'meta' => [
                'current_page'  => $page->currentPage(),
                'last_page'     => $page->lastPage(),
                'per_page'      => $page->perPage(),
                'total'         => $page->total(),
                'counts'        => [
                    'pending'  => (int) ($byStatus['pending'] ?? 0),
                    'approved' => (int) ($byStatus['approved'] ?? 0),
                    'rejected' => (int) ($byStatus['rejected'] ?? 0),
                ],
                'tong_hoa_hong' => (int) \App\Models\Commission::where('ctv_id', $userId)->sum('so_tien'),
            ],
        ]);
    }
}
