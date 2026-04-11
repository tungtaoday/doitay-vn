<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\Api\AccountInactiveException;
use App\Exceptions\Api\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Api/V1 AuthController.
 *
 * Implements DUC-AUTH-LOGIN, DUC-AUTH-REGISTER, DUC-AUTH-ME, DUC-AUTH-LOGOUT.
 *
 * Stays thin: validation lives in FormRequests, business logic in AuthService,
 * shaping in UserResource. This controller exists only to wire them together.
 */
class AuthController extends Controller
{
    public function __construct(private readonly AuthService $auth)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        [$user, $token] = $this->auth->register($request->validated());

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            [$user, $token] = $this->auth->login(
                $request->string('email')->toString(),
                $request->string('password')->toString(),
                $request,
            );
        } catch (InvalidCredentialsException) {
            return response()->json([
                'message' => __('Email hoặc mật khẩu không đúng'),
            ], 401);
        } catch (AccountInactiveException) {
            return response()->json([
                'message' => __('Tài khoản chưa được kích hoạt'),
            ], 403);
        }

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return (new UserResource($request->user()))
            ->response()
            ->setStatusCode(200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(null, 204);
    }
}
