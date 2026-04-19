<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateDepositRequest;
use App\Http\Requests\V1\User\UploadDepositProofRequest;
use App\Http\Resources\V1\User\DepositRequestResource;
use App\Services\DepositService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function __construct(private DepositService $deposits) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = min(100, max(1, (int) $request->input('per_page', 20)));
        $paginator = $this->deposits->listForUser($user, $perPage);

        return response()->json([
            'data' => DepositRequestResource::collection($paginator->items()),
            'stats' => $this->deposits->statsForUser($user),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ])->header('Cache-Control', 'no-store');
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $deposit = $this->deposits->find($request->user(), $id);

        return response()->json([
            'data' => new DepositRequestResource($deposit),
        ])->header('Cache-Control', 'no-store');
    }

    public function store(CreateDepositRequest $request): JsonResponse
    {
        try {
            $deposit = $this->deposits->createRequest(
                user: $request->user(),
                walletId: (int) $request->input('wallet_id'),
                paymentMethodId: (int) $request->input('payment_method_id'),
                amount: (float) $request->input('amount'),
                userNotes: $request->input('user_notes'),
                proof: $request->file('payment_proof'),
                idempotencyKey: $request->input('idempotency_key') ?: $request->header('Idempotency-Key'),
            );
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'data' => new DepositRequestResource($deposit->load(['wallet.company'])),
        ], 201);
    }

    public function uploadProof(UploadDepositProofRequest $request, int $id): JsonResponse
    {
        $deposit = $this->deposits->uploadProof(
            user: $request->user(),
            depositId: $id,
            proof: $request->file('payment_proof'),
        );

        return response()->json([
            'data' => new DepositRequestResource($deposit),
        ]);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $deposit = $this->deposits->cancel($request->user(), $id);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'data' => new DepositRequestResource($deposit),
        ]);
    }
}
