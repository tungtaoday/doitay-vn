<?php

namespace App\Http\Resources\V1\User;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WalletTransaction */
class WalletTransactionResource extends JsonResource
{
    private const TYPE_LABELS = [
        'welcome_bonus' => 'Thưởng đăng ký',
        'referral_bonus' => 'Thưởng giới thiệu',
        'lead_purchase' => 'Mua leads',
        'customer_info_access' => 'Phí truy cập thông tin',
        'admin_adjustment' => 'Điều chỉnh',
        'refund' => 'Hoàn tiền',
        'deposit' => 'Nạp tiền',
    ];

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->company_wallet_id,
            'company_name' => $this->wallet?->company?->name,
            'type' => $this->type,
            'transaction_type' => $this->transaction_type,
            'transaction_type_label' => self::TYPE_LABELS[$this->transaction_type] ?? $this->transaction_type,
            'amount' => (float) $this->amount,
            'signed_amount' => $this->type === 'credit' ? (float) $this->amount : -1 * (float) $this->amount,
            'balance_before' => (float) $this->balance_before,
            'balance_after' => (float) $this->balance_after,
            'description' => $this->description,
            'reference_id' => $this->reference_id,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
