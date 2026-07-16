<?php

namespace App\Services;

use App\Exceptions\Api\AccountInactiveException;
use App\Exceptions\Api\InvalidCredentialsException;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use App\Support\Identifier;
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
        $identifier = trim((string) ($data['identifier'] ?? $data['email'] ?? ''));
        $type = Identifier::detect($identifier);

        $attrs = [
            'name'              => $data['name'],
            'firstname'         => $data['firstname'] ?? $data['name'],
            'lastname'          => $data['lastname'] ?? '',
            'password'          => Hash::make($data['password']),
            'status'            => 1,
            'ev'                => 0,
            'sv'                => 0,
            'profile_complete'  => 0,
        ];

        $selectedRole = null;
        if (! empty($data['user_role']) && in_array($data['user_role'], ['customer', 'contractor', 'both'], true)) {
            $selectedRole = $data['user_role'];
        }

        if ($type === Identifier::TYPE_EMAIL) {
            $attrs['email'] = strtolower($identifier);
            $attrs['ev'] = 1; // email auto-verified for now (Phase 2 adds real verification)
        } else {
            $attrs['mobile'] = Identifier::normalizePhone($identifier);
            $attrs['dial_code'] = '+84';
            $attrs['country_code'] = 'VN';
            $attrs['country_name'] = 'Vietnam';
            $attrs['sv'] = 1; // phone auto-verified for now
            $attrs['email'] = strtolower($data['name']) . '_' . uniqid() . '@phone.doitay.local';
        }

        $user = User::create($attrs);

        if ($selectedRole !== null) {
            // Cache pending role for 30 days so the profile-completion step
            // can pre-check the "register_as_expert" checkbox.
            try {
                cache(['user_role:pending:' . $user->id => $selectedRole], now()->addDays(30));
            } catch (\Throwable) {
            }
        }

        try {
            notify($user, 'USER_WELCOME', [
                'fullname'  => $user->fullname ?? $user->name,
                'site'      => gs('site_name'),
                'login_url' => url('/dang-nhap'),
            ]);
        } catch (\Throwable) {
        }

        try {
            $admin = Admin::first();
            if ($admin) {
                notify($admin, 'ADMIN_NEW_USER', [
                    'fullname' => $user->fullname ?? $user->name,
                    'email'    => $user->email,
                    'date'     => now()->format('d/m/Y H:i:s'),
                    'ip'       => request()->ip(),
                    'user_url' => url('/admin/users/detail/' . $user->id),
                ], ['email']);
            }

            AdminNotification::create([
                'user_id'   => $user->id,
                'title'     => 'New member registered',
                'click_url' => urlPath('admin.users.detail', $user->id),
            ]);
        } catch (\Throwable) {
        }

        $token = $user->createToken('frontend', ['*'])->plainTextToken;

        return [$user, $token];
    }

    /**
     * @return array{0: User, 1: string}  [user, plainTextToken]
     *
     * @throws InvalidCredentialsException
     * @throws AccountInactiveException
     */
    public function login(string $identifier, string $password, ?Request $request = null): array
    {
        $type = Identifier::detect($identifier);

        $user = null;
        if ($type === Identifier::TYPE_EMAIL) {
            $user = User::where('email', strtolower($identifier))->first();
        } elseif ($type === Identifier::TYPE_PHONE) {
            $user = User::where('mobile', Identifier::normalizePhone($identifier))->first();
        }

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        if ((int) $user->status !== 1) {
            throw new AccountInactiveException();
        }

        $token = $user->createToken('frontend', ['*'])->plainTextToken;

        try {
            UserLogin::create([
                'user_id'    => $user->id,
                'user_ip'    => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (\Throwable) {
        }

        return [$user, $token];
    }

    /**
     * Find or create a user from an OAuth provider response.
     *
     * @param  array{provider:string,provider_id:string,email:?string,name:?string,image:?string}  $data
     * @return array{0: User, 1: string}
     */
    public function socialLogin(array $data, ?Request $request = null): array
    {
        $user = User::where('provider', $data['provider'])
            ->where('provider_id', $data['provider_id'])
            ->first();

        if (! $user && ! empty($data['email'])) {
            $user = User::where('email', strtolower($data['email']))->first();
            if ($user) {
                $user->update([
                    'provider'    => $data['provider'],
                    'provider_id' => $data['provider_id'],
                ]);
            }
        }

        if (! $user) {
            $user = User::create([
                'name'             => $data['name'] ?: 'User',
                'firstname'        => $data['name'] ?: 'User',
                'email'            => ! empty($data['email'])
                    ? strtolower($data['email'])
                    : $data['provider'] . '_' . $data['provider_id'] . '@social.doitay.local',
                'password'         => Hash::make(bin2hex(random_bytes(16))),
                'status'           => 1,
                'ev'               => 1,
                'sv'               => 0,
                'provider'         => $data['provider'],
                'provider_id'      => $data['provider_id'],
                'image'            => $data['image'] ?? null,
                'profile_complete' => 0,
            ]);
        }

        if ((int) $user->status !== 1) {
            throw new AccountInactiveException();
        }

        $token = $user->createToken('frontend', ['*'])->plainTextToken;

        try {
            UserLogin::create([
                'user_id'    => $user->id,
                'user_ip'    => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (\Throwable) {
        }

        return [$user, $token];
    }
}
