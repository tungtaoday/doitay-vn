<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Public\CreateThoProfileRequest;
use App\Services\MiniAppProfileService;
use Illuminate\Http\JsonResponse;

/**
 * API V1 — Public. Xuất bản hồ sơ thợ từ Zalo Mini App lên chợ doitay.
 * Route có rate-limit (xem v1_public.php). Không auth (giai đoạn này) — chống
 * spam bằng dedup SĐT + hàng đợi duyệt. Nâng cấp Zalo getPhoneNumber sau.
 */
class ThoProfileController extends Controller
{
    public function __construct(private readonly MiniAppProfileService $service)
    {
    }

    public function store(CreateThoProfileRequest $request): JsonResponse
    {
        $result = $this->service->publish($request->validated());
        $company = $result['company'];
        $live = (int) $company->status === Status::APPROVED;

        // Đo phễu Bắc Đẩu: thợ đưa hồ sơ lên chợ (an toàn — không chặn nếu lỗi).
        \App\Models\ProductEvent::log([
            'event'      => 'profile_published',
            'surface'    => 'tho',
            'channel'    => 'miniapp',
            'company_id' => $company->id,
            'actor_key'  => $request->validated()['zalo_id'] ?? null,
            'meta'       => ['review_status' => $live ? 'live' : 'pending', 'created' => $result['created']],
        ]);

        return response()->json([
            'data' => [
                'company_id'    => $company->id,
                'slug'          => \Illuminate\Support\Str::slug((string) $company->name),
                'review_status' => $live ? 'live' : 'pending',
                'is_live'       => $live,
                // URL chỉ mở được khi đã duyệt; FE tự kiểm tra qua GET companies/{id}.
                'profile_url'   => $this->service->profileUrl($company),
                'created'       => $result['created'],
            ],
        ], $result['created'] ? 201 : 200);
    }
}
