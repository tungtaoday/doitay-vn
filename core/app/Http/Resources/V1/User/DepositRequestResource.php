<?php

namespace App\Http\Resources\V1\User;

use App\Models\DepositRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin DepositRequest */
class DepositRequestResource extends JsonResource
{
    private const STATUS_LABELS = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'completed' => 'Hoàn thành',
        'rejected' => 'Từ chối',
        'cancelled' => 'Đã hủy',
    ];

    private const METHOD_LABELS = [
        'bank_transfer' => 'Chuyển khoản ngân hàng',
        'momo' => 'Ví MoMo',
        'zalopay' => 'Ví ZaloPay',
        'other' => 'Khác',
    ];

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'deposit_code' => $this->deposit_code,
            'wallet' => [
                'id' => $this->company_wallet_id,
                'company_name' => $this->wallet?->company?->name,
            ],
            'amount' => (float) $this->amount,
            'payment_method' => $this->payment_method,
            'payment_method_label' => self::METHOD_LABELS[$this->payment_method] ?? $this->payment_method,
            'status' => $this->status,
            'status_label' => self::STATUS_LABELS[$this->status] ?? $this->status,
            'user_notes' => $this->user_notes,
            'payment_proof_url' => $this->payment_proof
                ? Storage::disk('public')->url($this->payment_proof)
                : null,
            'rejection_reason' => $this->rejection_reason,
            'processed_at' => $this->processed_at?->toIso8601String(),
            'can_be_cancelled' => $this->canBeCancelled(),
            'can_upload_proof' => $this->status === 'pending',
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
