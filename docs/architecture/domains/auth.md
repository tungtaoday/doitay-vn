# Domain: Auth (User Aggregate)

> Domain doc lightweight cho rebuild headless. Eloquent model đã tồn tại từ Laravel app gốc — doc này map nó sang góc nhìn DDD và liệt kê invariant + commands/queries cần exposed qua `Api/V1/Auth*`.

## Aggregate Root: User

**Source**: `app/Models/User.php` (extends `Illuminate\Foundation\Auth\User`, dùng `HasApiTokens`).

### Fields chính (từ migration + `$fillable`)

| Field | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `username` | string nullable unique | login alt |
| `firstname`, `lastname`, `name` | string | display name |
| `email` | string unique | login chính |
| `password` | string (bcrypt hash) | hidden |
| `mobile` | string nullable | + country_code |
| `country_code`, `country_name` | string | |
| `address` (object), `city`, `district`, `ward`, `state`, `zip` | mixed | |
| `image` | string url | avatar |
| `status` | int | 0=banned, 1=active, ... (xem `App\Constants\Status`) |
| `ev` (email verified), `sv` (sms verified) | tinyint | |
| `email_verified_at`, `mobile_verified_at` | datetime | |
| `provider`, `provider_id` | string | OAuth (Socialite) |
| `referral_code`, `referred_by`, `referral_count`, `total_referral_earnings` | mixed | loyalty |
| `kyc_data` | json | |
| `ver_code`, `ver_code_send_at` | string/datetime | OTP |
| `ban_reason`, `about`, `profile_complete` | mixed | |
| `created_at`, `updated_at` | timestamps | |

### Hidden (KHÔNG bao giờ expose qua API)
`password`, `remember_token`, `ver_code`

### Relationships dùng cho rebuild

| Relation | Target | Use case |
|---|---|---|
| `loginLogs()` | `UserLogin` | DUC-AUTH-LOGIN log step |
| `tickets()` | `SupportTicket` | future user namespace |
| `notifications()` | morphMany Notification | future |

### Invariants

| INV | Rule | Enforcement |
|---|---|---|
| INV-USER-1 | Email unique case-insensitive | DB unique + `RegisterRequest` |
| INV-USER-2 | Password hash bcrypt, KHÔNG plaintext | `Hash::make()` ở Service |
| INV-USER-3 | `status = active` (1) thì mới được login | `AuthService::login` step 3 |
| INV-USER-4 | `password`, `remember_token`, `ver_code` không bao giờ ra API response | `$hidden` + Resource |

## Sanctum PAT

**Table**: `personal_access_tokens` (managed by `laravel/sanctum`).

| Concept | Decision cho rebuild |
|---|---|
| Mode | **Personal Access Token** (không phải SPA cookie mode) |
| Storage ở FE | httpOnly cookie ở Next.js, không bao giờ ở localStorage |
| Abilities | `['*']` mặc định, có thể siết khi mở mobile app |
| Name | `'frontend'` cho web, `'mobile'` cho future app |
| Revoke | `currentAccessToken()->delete()` ở logout — KHÔNG revoke all |

## Commands (write operations)

| Command | DUC | Endpoint |
|---|---|---|
| `RegisterUser` | DUC-AUTH-REGISTER | `POST /api/v1/auth/register` |
| `LoginUser` | DUC-AUTH-LOGIN | `POST /api/v1/auth/login` |
| `LogoutCurrentSession` | DUC-AUTH-LOGOUT | `POST /api/v1/auth/logout` |

## Queries (read operations)

| Query | DUC | Endpoint |
|---|---|---|
| `GetCurrentUser` | DUC-AUTH-ME | `GET /api/v1/auth/me` |

## Service Layer

**File**: `app/Services/AuthService.php` (mới — extract từ controller hiện hữu nếu có).

```php
class AuthService {
    public function register(array $data): array {
        // returns [User $user, string $plainTextToken]
    }
    public function login(string $email, string $password, Request $r): array {
        // returns [User $user, string $plainTextToken]
        // throws InvalidCredentialsException, AccountInactiveException
    }
    public function logoutCurrent(User $user, PersonalAccessToken $token): void;
}
```

## Out of scope (cho Phase 1 này)

- Forgot password / reset password (đã có ở Blade `PasswordReset` model — Phase 2)
- 2FA / OTP qua SMS (đã có infrastructure qua Twilio/Vonage — Phase 2)
- Social login OAuth (Socialite đã cài — Phase 2)
- Admin login (namespace riêng `Api/V1/Admin/AuthController`, Phase 3)

## References

- Eloquent: `core/app/Models/User.php`
- Migration gốc: `core/database/migrations/2014_10_12_000000_create_users_table.php` + nhiều `add_*_to_users_table` migrations sau đó
- Sanctum config: `core/config/sanctum.php`
- Sanctum migration: `core/database/migrations/2024_*_create_personal_access_tokens_table.php`
