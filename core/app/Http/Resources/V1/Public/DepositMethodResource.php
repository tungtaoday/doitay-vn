<?php

namespace App\Http\Resources\V1\Public;

use App\Models\DepositSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DepositSetting */
class DepositMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'payment_method' => $this->payment_method,
            'qr_code_url' => method_exists($this->resource, 'getQrCodeUrl') ? $this->getQrCodeUrl() : null,
            'bank' => [
                'name' => $this->bank_name,
                'branch' => $this->bank_branch,
                'account_number' => $this->account_number,
                'account_name' => $this->account_name,
            ],
            'wallet' => [
                'phone' => $this->wallet_phone,
                'name' => $this->wallet_name,
            ],
            'instructions' => $this->instructions,
            'note_template' => $this->note_template,
            'min_amount' => (float) $this->min_amount,
            'max_amount' => $this->max_amount ? (float) $this->max_amount : null,
            'processing_hours' => $this->processing_hours,
        ];
    }
}
