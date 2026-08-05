<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Public\ClaimThoProfileRequest;
use App\Http\Requests\V1\Public\CreateThoProfileRequest;
use App\Http\Requests\V1\Public\UploadThoImagesRequest;
use App\Http\Resources\V1\Public\ThoProfileResource;
use App\Models\Company;
use App\Services\MiniAppProfileService;
use App\Services\ThoPortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API V1 — Public. Hồ sơ thợ từ Zalo Mini App.
 *
 * SERVER LÀ NGUỒN SỰ THẬT: thợ đổi máy/cài lại app vẫn lấy lại được hồ sơ (`me`),
 * CTV dựng hộ rồi gửi link để thợ nhận (`claim`), ảnh việc lưu trên server chứ
 * không nằm trong máy thợ (`uploadImages`).
 *
 * Chưa có auth Zalo phía server: `zalo_id` do client khai. Chống lạm dụng bằng
 * rate-limit, vé claim dùng 1 lần có hạn, và quy tắc "hồ sơ đã có chủ thì người
 * khác không sửa được". TODO Phase 2: đổi access token Zalo lấy user id ở server
 * (cần ZALO_APP_SECRET) để định danh không thể giả mạo.
 */
class ThoProfileController extends Controller
{
    public function __construct(
        private readonly MiniAppProfileService $service,
        private readonly ThoPortfolioService $portfolio,
    ) {
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
                'updated'       => $result['updated'] ?? false,
            ],
        ], $result['created'] ? 201 : 200);
    }

    /**
     * GET /public/tho-profiles/me?zalo_id=...
     * Khôi phục hồ sơ khi thợ mở app trên máy mới. Chưa có hồ sơ → 404.
     */
    public function me(Request $request): JsonResponse
    {
        $zaloId = trim((string) $request->query('zalo_id', ''));
        if ($zaloId === '' || mb_strlen($zaloId) > 64) {
            return response()->json(['message' => 'Thiếu zalo_id'], 422);
        }

        $company = $this->service->findByZaloId($zaloId);
        if (! $company) {
            return response()->json(['message' => 'Chưa có hồ sơ'], 404);
        }

        $company->loadMissing(['category', 'portfolios']);

        return response()->json(['data' => new ThoProfileResource($company)]);
    }

    /**
     * POST /public/tho-profiles/{id}/claim
     * Thợ bấm link CTV gửi → nhận quyền sở hữu hồ sơ đã được dựng hộ.
     */
    public function claim(ClaimThoProfileRequest $request, int $id): JsonResponse
    {
        $company = Company::find($id);
        if (! $company) {
            return response()->json(['message' => 'Không tìm thấy hồ sơ'], 404);
        }

        $data = $request->validated();
        $result = $this->service->claim($company, $data['token'], $data['zalo_id']);

        if (! $result['ok']) {
            $map = [
                'invalid_token'   => ['Link không hợp lệ hoặc đã dùng rồi', 422],
                'expired_token'   => ['Link đã hết hạn — nhờ người gửi tạo link mới', 410],
                'already_claimed' => ['Hồ sơ này đã có chủ', 409],
                'missing_zalo_id' => ['Không lấy được tài khoản Zalo', 422],
            ];
            [$msg, $code] = $map[$result['error']] ?? ['Không nhận được hồ sơ', 422];

            return response()->json(['message' => $msg, 'error' => $result['error']], $code);
        }

        $company = $result['company'];
        \App\Models\ProductEvent::log([
            'event'      => 'profile_claimed',
            'surface'    => 'tho',
            'channel'    => 'miniapp',
            'company_id' => $company->id,
            'actor_key'  => $data['zalo_id'],
        ]);

        $company->loadMissing(['category', 'portfolios']);

        return response()->json(['data' => new ThoProfileResource($company)]);
    }

    /**
     * POST /public/tho-profiles/{id}/images
     * Tải ảnh việc lên server. Chỉ chủ hồ sơ (hoặc người cầm vé còn hạn) được tải.
     */
    public function uploadImages(UploadThoImagesRequest $request, int $id): JsonResponse
    {
        $company = Company::find($id);
        if (! $company) {
            return response()->json(['message' => 'Không tìm thấy hồ sơ'], 404);
        }

        $data = $request->validated();
        if (! $this->service->mayEdit($company, (string) ($data['zalo_id'] ?? ''), $data['token'] ?? null)) {
            return response()->json(['message' => 'Không có quyền sửa hồ sơ này'], 403);
        }

        // Ảnh chân dung (nếu có) — thứ khách tin nhất trên hồ sơ
        $avatar = null;
        if ($request->hasFile('avatar')) {
            $avatar = $this->portfolio->setAvatar($company, $request->file('avatar'));
        }

        $result = $this->portfolio->addImages(
            $company,
            $request->file('images', []),
            $request->input('titles', []),
        );

        return response()->json([
            'data' => [
                'saved'  => $result['saved'],
                'avatar' => $avatar ? asset('assets/images/company/' . $avatar) : null,
                'images' => $this->portfolio->listImages($company),
            ],
        ], 201);
    }
}
