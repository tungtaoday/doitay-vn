<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public-safe user shape returned to authenticated frontend.
 *
 * NEVER add password, remember_token, ver_code, kyc_data, or admin-only fields.
 * If you need an admin view of a user, create a separate AdminUserResource.
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name ?? trim(($this->firstname ?? '') . ' ' . ($this->lastname ?? '')),
            'username'         => $this->username,
            'email'            => $this->email,
            'mobile'           => $this->mobile,
            'avatar'           => $this->image
                ? asset('assets/images/user/profile/' . $this->image)
                : null,
            'profile_complete' => (bool) $this->profile_complete,
            'has_company'      => $this->companies()->exists(),
            'pending_role'     => cache('user_role:pending:' . $this->id),
            'ev'               => (bool) $this->ev,
            'sv'               => (bool) $this->sv,
            'location'         => [
                'city'     => $this->city,
                'district' => $this->district,
                'ward'     => $this->ward,
                'address'  => $this->address,
            ],
            'role'             => 'user',
            'status'           => (int) $this->status,
            'created_at'       => optional($this->created_at)->toIso8601String(),
        ];
    }
}
