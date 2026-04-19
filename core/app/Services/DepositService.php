<?php

namespace App\Services;

use App\Models\DepositRequest;
use App\Models\DepositSetting;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepositService
{
    public function __construct(private WalletService $wallets) {}

    public function listForUser(User $user, int $perPage = 20): LengthAwarePaginator
    {
        return DepositRequest::where('user_id', $user->id)
            ->with(['wallet.company:id,name', 'processedBy:id,username'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(User $user, int $id): DepositRequest
    {
        return DepositRequest::where('user_id', $user->id)
            ->with(['wallet.company:id,name', 'processedBy:id,username'])
            ->findOrFail($id);
    }

    public function statsForUser(User $user): array
    {
        $base = DepositRequest::where('user_id', $user->id);

        return [
            'total_requests' => (clone $base)->count(),
            'completed_requests' => (clone $base)->where('status', 'completed')->count(),
            'pending_requests' => (clone $base)->where('status', 'pending')->count(),
            'total_deposited' => (float) (clone $base)->where('status', 'completed')->sum('amount'),
        ];
    }

    /**
     * Create deposit request. Idempotent by `idempotency_key` metadata passed
     * via $context — if the same user submits the same key twice, the existing
     * request is returned instead of creating a duplicate.
     */
    public function createRequest(
        User $user,
        int $walletId,
        int $paymentMethodId,
        float $amount,
        ?string $userNotes = null,
        ?UploadedFile $proof = null,
        ?string $idempotencyKey = null
    ): DepositRequest {
        $wallet = $this->wallets->assertOwnership($user, $walletId);
        $method = DepositSetting::where('is_active', true)->findOrFail($paymentMethodId);

        if ($amount < (float) $method->min_amount || ($method->max_amount && $amount > (float) $method->max_amount)) {
            throw new \RuntimeException("Số tiền phải từ {$method->min_amount} đến {$method->max_amount}");
        }

        if ($idempotencyKey) {
            $existing = DepositRequest::where('user_id', $user->id)
                ->where('deposit_code', $this->idempotencyToCode($user->id, $idempotencyKey))
                ->first();
            if ($existing) {
                return $existing;
            }
        }

        return DB::transaction(function () use ($user, $wallet, $method, $amount, $userNotes, $proof, $idempotencyKey) {
            $proofPath = null;
            if ($proof) {
                $proofPath = $proof->store('deposit_proofs', 'public');
            }

            return DepositRequest::create([
                'company_wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'deposit_code' => $idempotencyKey
                    ? $this->idempotencyToCode($user->id, $idempotencyKey)
                    : DepositRequest::generateDepositCode($user->id),
                'amount' => $amount,
                'payment_method' => $method->payment_method,
                'payment_proof' => $proofPath,
                'user_notes' => $userNotes,
                'status' => 'pending',
            ]);
        });
    }

    public function uploadProof(User $user, int $depositId, UploadedFile $proof): DepositRequest
    {
        $deposit = DepositRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->findOrFail($depositId);

        if ($deposit->payment_proof) {
            Storage::disk('public')->delete($deposit->payment_proof);
        }

        $path = $proof->store('deposit_proofs', 'public');
        $deposit->update(['payment_proof' => $path]);

        return $deposit->fresh(['wallet.company']);
    }

    public function cancel(User $user, int $depositId): DepositRequest
    {
        $deposit = DepositRequest::where('user_id', $user->id)->findOrFail($depositId);

        if (! $deposit->canBeCancelled()) {
            throw new \RuntimeException('Không thể hủy yêu cầu ở trạng thái hiện tại');
        }

        $deposit->cancel('Hủy bởi người dùng qua API');

        return $deposit->fresh(['wallet.company']);
    }

    private function idempotencyToCode(int $userId, string $key): string
    {
        return 'IDP' . str_pad((string) $userId, 4, '0', STR_PAD_LEFT) . substr(hash('sha256', $key), 0, 16);
    }
}
