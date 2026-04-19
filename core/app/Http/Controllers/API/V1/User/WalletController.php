<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\WalletResource;
use App\Http\Resources\V1\User\WalletTransactionResource;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(private WalletService $wallets) {}

    public function overview(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $this->wallets->overviewForUser($user);

        return response()->json([
            'data' => [
                'wallets' => WalletResource::collection($data['wallets']),
                'totals' => $data['totals'],
                'wallet_count' => $data['wallet_count'],
            ],
        ])->header('Cache-Control', 'no-store');
    }

    public function transactions(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = min(100, max(1, (int) $request->input('per_page', 20)));

        $paginator = $this->wallets->listTransactionsForUser(
            $user,
            $request->only(['transaction_type', 'type', 'date_from', 'date_to', 'wallet_id']),
            $perPage
        );

        return response()->json([
            'data' => WalletTransactionResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ])->header('Cache-Control', 'no-store');
    }
}
