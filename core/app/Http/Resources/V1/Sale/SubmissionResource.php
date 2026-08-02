<?php

namespace App\Http\Resources\V1\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape hồ sơ thợ trả cho CTV/Quản lý. KHÔNG lộ dữ liệu nội bộ ngoài phạm vi.
 * `anh` (danh sách URL) chỉ trả khi relation images đã load — bắt buộc cho
 * màn duyệt (Quản lý phải NHÌN ảnh mới nghiệm thu được, BR-2).
 * Ref: DUC-SUBMISSION-CREATE, DUC-SUBMISSION-LIST, DUC-SUBMISSION-APPROVE.
 */
class SubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            // lam_ho = CTV dựng hộ tại chỗ · da_mo = thợ tự mở, CTV khai nhận công
            'loai'          => $this->loai ?? 'lam_ho',
            'ten_tho'       => $this->ten_tho,
            'nghe'          => $this->nghe,
            'khu_vuc'       => $this->khu_vuc,
            'sdt_tho'       => $this->sdt_tho,
            'nam_kn'        => $this->nam_kn,
            'bang_gia'      => $this->bang_gia,
            'status'        => $this->status,
            'ly_do_tu_choi' => $this->ly_do_tu_choi,
            'company_id'    => $this->company_id,
            // Link CTV gửi cho thợ: thợ bấm là Mini App mở sẵn hồ sơ đã dựng hộ.
            // Null khi hồ sơ đã có chủ (thợ nhận rồi) hoặc chưa dựng được.
            'claim_link'    => $this->claimLink(),
            'so_anh'        => $this->images_count
                ?? ($this->relationLoaded('images') ? $this->images->count() : 0),
            'anh'           => $this->whenLoaded('images', fn () => $this->images->map(
                fn ($img) => ['id' => $img->id, 'url' => $img->url],
            )->all()),
            'created_at'    => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * Link mở Mini App kèm vé nhận hồ sơ. Dạng deeplink Zalo:
     * https://zalo.me/s/<app_id>/?tho=<company_id>&claim=<token>
     * Cấu hình `services.zalo.miniapp_id` (env ZALO_MINIAPP_ID).
     */
    private function claimLink(): ?string
    {
        $company = $this->company_id
            ? \App\Models\Company::select('id', 'claim_token', 'claim_expires_at', 'zalo_id')->find($this->company_id)
            : null;

        if (! $company || ! $company->claim_token) {
            return null;
        }
        if ($company->claim_expires_at && $company->claim_expires_at->isPast()) {
            return null;
        }

        $appId = config('services.zalo.miniapp_id');
        $base = $appId
            ? 'https://zalo.me/s/' . $appId . '/'
            : rtrim((string) config('app.frontend_url', config('app.url')), '/') . '/mini/';

        return $base . '?tho=' . $company->id . '&claim=' . $company->claim_token;
    }
}
