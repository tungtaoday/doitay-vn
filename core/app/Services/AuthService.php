<?php

namespace App\Services;

use App\Exceptions\Api\AccountInactiveException;
use App\Exceptions\Api\InvalidCredentialsException;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Authentication service shared by Api/V1/AuthController and any future
 * Blade or admin login flow that wants to honor the same invariants.
 *
 * Single source of truth for:
 *   - hashing rules
 *   - active-status enforcement
 *   - login audit logging (UserLogin)
 *   - Sanctum PAT issuance
 *
 * Throws domain exceptions; controllers map them to HTTP responses.
 */
class AuthService
{
    /**
     * @return array{0: User, 1: string}  [user, plainTextToken]
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name'              => $data['name'],
            'firstname'         => $data['name'],
            'email'             => strtolower($data['email']),
            'password'          => Hash::make($data['password']),
            'status'            => 1,         // active
            'ev'                => 1,         // mark email verified for now (Phase 2 will add real verification)
            'sv'                => 0,
            'profile_complete'  => 0,
        ]);

        $token = $user->createToken('frontend', ['*'])->plainTextToken;

        return [$user, $token];
    }

    /**
     * @return array{0: User, 1: string}  [user, plainTextToken]
     *
     * @throws InvalidCredentialsException When email unknown or password mismatch.
     * @throws AccountInactiveException    When user exists but status != 1.
     */
    public function login(string $email, string $password, ?Request $request = null): array
    {
        $user = User::where('email', strtolower($email))->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        if ((int) $user->status !== 1) {
            throw new AccountInactiveException();
        }

        $token = $user->createToken('frontend', ['*'])->plainTextToken;

        // Best-effort audit log; never fail the login because of it.
        try {
            UserLogin::create([
                'user_id'    => $user->id,
                'user_ip'    => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (\Throwable) {
            // swallow — UserLogin schema may diverge across environments.
        }

        return [$user, $token];
    }
}
