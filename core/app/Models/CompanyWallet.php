<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'balance',
        'currency',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the wallet.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all transactions for this wallet
     */
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get credit transactions
     */
    public function credits()
    {
        return $this->hasMany(WalletTransaction::class)->where('type', 'credit');
    }

    /**
     * Get debit transactions
     */
    public function debits()
    {
        return $this->hasMany(WalletTransaction::class)->where('type', 'debit');
    }

    /**
     * Add funds to the wallet with transaction tracking
     */
    public function addFunds($amount, $transactionType = 'admin_adjustment', $description = null, $metadata = null, $referenceId = null)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }
        
        return WalletTransaction::createTransaction(
            $this->id,
            'credit',
            $amount,
            $transactionType,
            $description ?: "Nạp tiền vào ví",
            $metadata,
            $referenceId
        );
    }

    /**
     * Deduct funds from the wallet with transaction tracking
     */
    public function deductFunds($amount, $transactionType = 'lead_purchase', $description = null, $metadata = null, $referenceId = null)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }

        if ($this->balance < $amount) {
            throw new \InvalidArgumentException('Insufficient funds');
        }

        return WalletTransaction::createTransaction(
            $this->id,
            'debit',
            $amount,
            $transactionType,
            $description ?: "Rút tiền từ ví",
            $metadata,
            $referenceId
        );
    }

    /**
     * Purchase a lead
     */
    public function purchaseLead(Lead $lead, Company $company)
    {
        if (!$lead->canBePurchasedBy($company->id)) {
            throw new \Exception('Lead không thể mua được');
        }

        if ($this->balance < $lead->lead_price) {
            throw new \Exception('Số dư không đủ để mua lead này');
        }

        \DB::transaction(function () use ($lead, $company) {
            // Deduct from wallet
            $transaction = $this->deductFunds(
                $lead->lead_price,
                'lead_purchase',
                "Mua lead: {$lead->title}",
                ['lead_id' => $lead->id, 'company_id' => $company->id],
                "lead_{$lead->id}"
            );

            // Create lead purchase record
            $purchase = LeadPurchase::create([
                'lead_id' => $lead->id,
                'company_id' => $company->id,
                'user_id' => $company->user_id,
                'price_paid' => $lead->lead_price
            ]);

            // Update lead purchased count
            $lead->markAsPurchased();

            return $purchase;
        });
    }

    /**
     * Add welcome bonus for new companies
     */
    public function addWelcomeBonus($amount = 100000)
    {
        return $this->addFunds(
            $amount,
            'welcome_bonus',
            'Thưởng chào mừng - 10 leads miễn phí',
            ['bonus_type' => 'welcome', 'leads_count' => 10]
        );
    }

    /**
     * Add referral bonus
     */
    public function addReferralBonus($amount, $referredUserId, $referralType = 'signup')
    {
        return $this->addFunds(
            $amount,
            'referral_bonus',
            "Thưởng giới thiệu - {$referralType}",
            [
                'referred_user_id' => $referredUserId,
                'referral_type' => $referralType
            ]
        );
    }

    /**
     * Check if wallet has sufficient funds
     */
    public function hasSufficientFunds($amount)
    {
        return $this->balance >= $amount;
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalance()
    {
        return number_format($this->balance, 0, '.', ',') . ' VNĐ';
    }

    /**
     * Get total spent on leads
     */
    public function getTotalLeadSpending()
    {
        return $this->transactions()
            ->where('transaction_type', 'lead_purchase')
            ->where('type', 'debit')
            ->sum('amount');
    }

    /**
     * Get total bonuses received
     */
    public function getTotalBonuses()
    {
        return $this->transactions()
            ->whereIn('transaction_type', ['welcome_bonus', 'referral_bonus'])
            ->where('type', 'credit')
            ->sum('amount');
    }

    /**
     * Get spending this month
     */
    public function getMonthlySpending()
    {
        return $this->transactions()
            ->where('transaction_type', 'lead_purchase')
            ->where('type', 'debit')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    /**
     * Get recent transactions
     */
    public function getRecentTransactions($limit = 10)
    {
        return $this->transactions()
            ->with(['processedBy'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Create wallet for company if not exists
     */
    public static function createForCompany(Company $company, $initialBalance = 0)
    {
        $wallet = self::firstOrCreate(
            ['company_id' => $company->id],
            [
                'balance' => $initialBalance,
                'currency' => 'VND',
                'is_active' => true,
                'notes' => 'Ví được tạo tự động'
            ]
        );

        // Add welcome bonus if it's a new wallet and company is approved
        if ($wallet->wasRecentlyCreated && $company->status === \App\Constants\Status::APPROVED) {
            $wallet->addWelcomeBonus();
        }

        return $wallet;
    }
}
