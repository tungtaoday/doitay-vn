<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DepositRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_wallet_id',
        'user_id',
        'deposit_code',
        'amount',
        'payment_method',
        'status',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'transaction_reference',
        'payment_date',
        'payment_proof',
        'user_notes',
        'processed_by',
        'admin_notes',
        'processed_at',
        'rejection_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'processed_at' => 'datetime'
    ];

    // Relationships
    public function wallet()
    {
        return $this->belongsTo(CompanyWallet::class, 'company_wallet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Static methods
    public static function generateDepositCode($userId)
    {
        do {
            $code = 'DEP' . date('Ymd') . str_pad($userId, 4, '0', STR_PAD_LEFT) . strtoupper(Str::random(4));
        } while (self::where('deposit_code', $code)->exists());

        return $code;
    }

    // Instance methods
    public function getStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge badge--warning">Chờ xử lý</span>',
            'processing' => '<span class="badge badge--info">Đang xử lý</span>',
            'completed' => '<span class="badge badge--success">Hoàn thành</span>',
            'rejected' => '<span class="badge badge--danger">Từ chối</span>',
            'cancelled' => '<span class="badge badge--secondary">Đã hủy</span>'
        ];

        return $badges[$this->status] ?? '';
    }

    public function getPaymentMethodName()
    {
        $methods = [
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'momo' => 'Ví MoMo',
            'zalopay' => 'Ví ZaloPay',
            'other' => 'Khác'
        ];

        return $methods[$this->payment_method] ?? 'Không xác định';
    }

    public function getFormattedAmount()
    {
        return number_format($this->amount, 0, '.', ',') . ' VNĐ';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function canBeProcessed()
    {
        return $this->status === 'pending';
    }

    public function markAsProcessing($adminId = null)
    {
        $this->update([
            'status' => 'processing',
            'processed_by' => $adminId,
            'processed_at' => now()
        ]);
    }

    public function approve($adminId = null, $adminNotes = null)
    {
        \DB::transaction(function () use ($adminId, $adminNotes) {
            // Add funds to wallet
            $this->wallet->addFunds(
                $this->amount,
                'deposit',
                "Nạp tiền từ yêu cầu #{$this->deposit_code}",
                [
                    'deposit_request_id' => $this->id,
                    'payment_method' => $this->payment_method
                ],
                $this->deposit_code
            );

            // Update deposit request
            $this->update([
                'status' => 'completed',
                'processed_by' => $adminId,
                'processed_at' => now(),
                'admin_notes' => $adminNotes
            ]);
        });
    }

    public function reject($reason, $adminId = null, $adminNotes = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'processed_by' => $adminId,
            'processed_at' => now(),
            'admin_notes' => $adminNotes
        ]);
    }

    public function cancel($reason = null)
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('Không thể hủy yêu cầu này');
        }

        $this->update([
            'status' => 'cancelled',
            'rejection_reason' => $reason ?: 'Hủy bởi người dùng'
        ]);
    }
}
