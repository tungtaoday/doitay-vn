<?php

namespace App\Http\Resources\V1\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape hồ sơ thợ trả cho CTV. KHÔNG lộ dữ liệu nội bộ ngoài phạm vi CTV.
 * Ref: DUC-SUBMISSION-CREATE, DUC-SUBMISSION-LIST.
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
            'status'        => $this->status,
            'ly_do_tu_choi' => $this->ly_do_tu_choi,
            'so_anh'        => $this->images_count
                ?? ($this->relationLoaded('images') ? $this->images->count() : 0),
            'created_at'    => $this->created_at?->toIso8601String(),
        ];
    }
}
