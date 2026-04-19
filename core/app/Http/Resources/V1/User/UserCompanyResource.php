<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Company shape returned to its owner via /user/companies/*.
 *
 * Includes status fields (pending/approved/rejected) but NEVER admin_feedback,
 * which is reserved for the admin view.
 */
class UserCompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'experience'  => (int) $this->experience,
            'image'       => $this->image
                ? asset('assets/images/company/' . $this->image)
                : null,
            'status'      => (int) $this->status,
            'status_label' => match ((int) $this->status) {
                1 => 'Đã duyệt',
                2 => 'Chờ duyệt',
                3 => 'Bị từ chối',
                default => 'Không rõ',
            },
            'location'    => [
                'city'     => $this->city,
                'district' => $this->district,
                'ward'     => $this->ward,
                'address'  => $this->address,
            ],
            'tags'        => $this->tags ?? [],
            'services'    => $this->services ?? [],
            'created_at'  => optional($this->created_at)->toIso8601String(),
        ];
    }
}
