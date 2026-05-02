<?php

namespace App\Services;

use App\Models\CompanyWallet;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Wallets owned by a user (via their companies), eager-loaded with company.
     * Auto-creates a wallet for any approved company that doesn't have one yet.
     */
    public function walletsForUser(User $user): Collection
    {
        $companies = $user->companies()->with('wallet')->get();

        foreach ($companies as $company) {
            if ($company->status === \App\Constants\Status::APPROVED && ! $company->wallet) {
                CompanyWallet::createForCompany($company);
            }
        }

        return CompanyWallet::whereIn('company_id', $companies->pluck('id'))
            ->with('company:id,name,user_id')
            ->orderBy('id')
            ->get();
    }

    /**
     * Overview across all wallets for the user.
     */
    public function overviewForUser(User $user): array
    {
        $wallets = $this->walletsForUser($user);

        return [
            'wallets' => $wallets,
            'totals' => [
                'balance' => (float) $wallets->sum('balance'),
                'spent_total' => (float) $wallets->sum(fn ($w) => $w->getTotalLeadSpending()),
                'bonuses_total' => (float) $wallets->sum(fn ($w) => $w->getTotalBonuses()),
                'spent_this_month' => (float) $wallets->sum(fn ($w) => $w->getMonthlySpending()),
            ],
            'wallet_count' => $wallets->count(),
        ];
    }

    /**
     * Paginated transaction list across all of user's wallets, with filters.
     */
    public function listTransactionsForUser(
        User $user,
        array $filters = [],
        int $perPage = 20
    ): LengthAwarePaginator {
        $companyIds = $user->companies()->pluck('id');

        $query = WalletTransaction::query()
            ->whereHas('wallet', fn ($q) => $q->whereIn('company_id', $companyIds))
            ->with('wallet.company:id,name');

        if (! empty($filters['transaction_type'])) {
            $query->where('transaction_type', $filters['transaction_type']);
        }

        if (! empty($filters['type']) && in_array($filters['type'], ['credit', 'debit'], true)) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['wallet_id'])) {
            $query->where('company_wallet_id', $filters['wallet_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Credit wallet with lockForUpdate to prevent race conditions.
     *
     * This replaces the raw WalletTransaction::createTransaction() pattern,
     * which reads balance without a lock and is susceptible to lost-update
     * bugs under concurrent deposits/purchases.
     */
    public function creditWithLock(
        int $walletId,
        float $amount,
        string $transactionType,
        string $description,
        ?array $metadata = null,
        ?string $referenceId = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }

        return DB::transaction(function () use ($walletId, $amount, $transactionType, $description, $metadata, $referenceId) {
            $wallet = CompanyWallet::lockForUpdate()->findOrFail($walletId);

            $before = (float) $wallet->balance;
            $after = $before + $amount;

            $tx = WalletTransaction::create([
                'company_wallet_id' => $walletId,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'transaction_type' => $transactionType,
                'description' => $description,
                'metadata' => $metadata,
                'reference_id' => $referenceId,
                'status' => 'completed',
            ]);

            $wallet->balance = $after;
            $wallet->save();

            return $tx;
        });
    }

    /**
     * Debit wallet with lockForUpdate + balance check inside the lock.
     */
    public function debitWithLock(
        int $walletId,
        float $amount,
        string $transactionType,
        string $description,
        ?array $metadata = null,
        ?string $referenceId = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }

        return DB::transaction(function () use ($walletId, $amount, $transactionType, $description, $metadata, $referenceId) {
            $wallet = CompanyWallet::lockForUpdate()->findOrFail($walletId);

            $before = (float) $wallet->balance;
            if ($before < $amount) {
                throw new \RuntimeException('Insufficient funds');
            }

            $after = $before - $amount;

            $tx = WalletTransaction::create([
                'company_wallet_id' => $walletId,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'transaction_type' => $transactionType,
                'description' => $description,
                'metadata' => $metadata,
                'reference_id' => $referenceId,
                'status' => 'completed',
            ]);

            $wallet->balance = $after;
            $wallet->save();

            return $tx;
        });
    }

    /**
     * Verify a wallet belongs to the user (via company ownership).
     */
    public function assertOwnership(User $user, int $walletId): CompanyWallet
    {
        return CompanyWallet::whereHas('company', fn ($q) => $q->where('user_id', $user->id))
            ->findOrFail($walletId);
    }
}
