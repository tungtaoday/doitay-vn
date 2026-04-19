<?php

namespace App\Http\Resources\V1\User;

use App\Models\CompanyWallet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CompanyWallet */
class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company?->id,
                'name' => $this->company?->name,
            ],
            'balance' => (float) $this->balance,
            'currency' => $this->currency ?? 'VND',
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
