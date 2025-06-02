<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'transaction_type',
        'description',
        'metadata',
        'reference_id',
        'status',
        'processed_by',
        'admin_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array'
    ];

    // Relationships
    public function wallet()
    {
        return $this->belongsTo(CompanyWallet::class, 'company_wallet_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    // Scopes
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeByType($query, $transactionType)
    {
        return $query->where('transaction_type', $transactionType);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Methods
    public function getTypeBadge()
    {
        $badges = [
            'credit' => '<span class="badge badge--success">+ Tiền vào</span>',
            'debit' => '<span class="badge badge--danger">- Tiền ra</span>'
        ];

        return $badges[$this->type] ?? '';
    }

    public function getTransactionTypeBadge()
    {
        $badges = [
            'welcome_bonus' => '<span class="badge badge--info">Thưởng đăng ký</span>',
            'referral_bonus' => '<span class="badge badge--primary">Thưởng giới thiệu</span>',
            'lead_purchase' => '<span class="badge badge--warning">Mua leads</span>',
            'customer_info_access' => '<span class="badge badge--dark">Phí truy cập</span>',
            'admin_adjustment' => '<span class="badge badge--secondary">Điều chỉnh</span>',
            'refund' => '<span class="badge badge--success">Hoàn tiền</span>'
        ];

        return $badges[$this->transaction_type] ?? '';
    }

    public function getStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge badge--warning">Chờ xử lý</span>',
            'completed' => '<span class="badge badge--success">Hoàn thành</span>',
            'failed' => '<span class="badge badge--danger">Thất bại</span>',
            'cancelled' => '<span class="badge badge--secondary">Đã hủy</span>'
        ];

        return $badges[$this->status] ?? '';
    }

    public function getFormattedAmount()
    {
        $prefix = $this->type === 'credit' ? '+' : '-';
        return $prefix . number_format($this->amount, 0, '.', ',') . ' VNĐ';
    }

    public static function createTransaction($walletId, $type, $amount, $transactionType, $description, $metadata = null, $referenceId = null)
    {
        $wallet = CompanyWallet::findOrFail($walletId);
        $balanceBefore = $wallet->balance;

        // Calculate new balance
        if ($type === 'credit') {
            $balanceAfter = $balanceBefore + $amount;
        } else {
            $balanceAfter = $balanceBefore - $amount;
        }

        // Create transaction record
        $transaction = self::create([
            'company_wallet_id' => $walletId,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'transaction_type' => $transactionType,
            'description' => $description,
            'metadata' => $metadata,
            'reference_id' => $referenceId,
            'status' => 'completed'
        ]);

        // Update wallet balance
        $wallet->balance = $balanceAfter;
        $wallet->save();

        return $transaction;
    }

    public function canBeReversed()
    {
        return $this->status === 'completed' && 
               in_array($this->transaction_type, ['admin_adjustment', 'refund']) &&
               $this->created_at->diffInDays(now()) <= 30;
    }

    public function reverse($reason)
    {
        if (!$this->canBeReversed()) {
            throw new \Exception('Transaction cannot be reversed');
        }

        $reverseType = $this->type === 'credit' ? 'debit' : 'credit';
        
        return self::createTransaction(
            $this->company_wallet_id,
            $reverseType,
            $this->amount,
            'admin_adjustment',
            'Hoàn ngược giao dịch: ' . $reason,
            ['original_transaction_id' => $this->id, 'reason' => $reason],
            'reverse_' . $this->id
        );
    }
}
