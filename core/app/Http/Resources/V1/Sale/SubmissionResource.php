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
            'ten_tho'       => $this->ten_tho,
            'nghe'          => $this->nghe,
            'khu_vuc'       => $this->khu_vuc,
            'sdt_tho'       => $this->sdt_tho,
            'nam_kn'        => $this->nam_kn,
            'bang_gia'      => $this->bang_gia,
            'status'        => $this->status,
            'ly_do_tu_choi' => $this->ly_do_tu_choi,
            'company_id'    => $this->company_id,
            'so_anh'        => $this->images_count
                ?? ($this->relationLoaded('images') ? $this->images->count() : 0),
            'anh'           => $this->whenLoaded('images', fn () => $this->images->map(
                fn ($img) => ['id' => $img->id, 'url' => $img->url],
            )->all()),
            'created_at'    => $this->created_at?->toIso8601String(),
        ];
    }
}
