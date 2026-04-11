<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Feature tests for Api/V1 Auth endpoints.
 *
 * Covers DUC-AUTH-LOGIN, DUC-AUTH-REGISTER, DUC-AUTH-ME, DUC-AUTH-LOGOUT.
 *
 * @group api-v1
 * @group auth
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function activeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'email' => 'user@test.local',
            'password' => Hash::make('secret123'),
            'status' => 1, // active
            'ev' => 1,
            'sv' => 1,
        ], $attrs));
    }

    // ───────────────────────────── REGISTER (DUC-AUTH-REGISTER) ─────────────────────────────

    /** @test AC1: register success */
    public function register_success_returns_201_with_token_and_user(): void
    {
        $payload = [
            'name' => 'Tung',
            'email' => 'newuser@test.local',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', ['email' => 'newuser@test.local']);
    }

    /** @test AC2: duplicate email */
    public function register_with_duplicate_email_returns_422(): void
    {
        $this->activeUser(['email' => 'dup@test.local']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'X',
            'email' => 'dup@test.local',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    /** @test AC3: weak password */
    public function register_with_weak_password_returns_422(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'X',
            'email' => 'weak@test.local',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    /** @test AC4: password mismatch */
    public function register_with_password_mismatch_returns_422(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'X',
            'email' => 'mm@test.local',
            'password' => 'password123',
            'password_confirmation' => 'different456',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    // ───────────────────────────── LOGIN (DUC-AUTH-LOGIN) ─────────────────────────────

    /** @test AC1: login success */
    public function login_success_returns_token_and_user(): void
    {
        $this->activeUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'user@test.local',
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'email']]);
    }

    /** @test AC3+AC4: invalid credentials return 401 (no info leak) */
    public function login_with_wrong_password_returns_401(): void
    {
        $this->activeUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'user@test.local',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    /** @test AC3: nonexistent email returns 401 (same shape as wrong password) */
    public function login_with_unknown_email_returns_401(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nope@test.local',
            'password' => 'whatever',
        ]);

        $response->assertStatus(401);
    }

    /** @test AC2: validation */
    public function login_with_invalid_email_format_returns_422(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'not-an-email',
            'password' => 'whatever',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    /** @test AC5: inactive user returns 403 */
    public function login_with_inactive_user_returns_403(): void
    {
        $this->activeUser(['email' => 'inactive@test.local', 'status' => 0]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'inactive@test.local',
            'password' => 'secret123',
        ]);

        $response->assertStatus(403);
    }

    // ───────────────────────────── ME (DUC-AUTH-ME) ─────────────────────────────

    /** @test AC1: authenticated me */
    public function me_returns_current_user_when_authenticated(): void
    {
        $user = $this->activeUser();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('data.email', 'user@test.local');
    }

    /** @test AC2: unauthenticated */
    public function me_without_token_returns_401(): void
    {
        $response = $this->getJson('/api/v1/auth/me');
        $response->assertStatus(401);
    }

    /** @test AC4: never leak password */
    public function me_response_never_contains_password_or_remember_token(): void
    {
        $user = $this->activeUser();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me');

        $body = $response->getContent();
        $this->assertStringNotContainsString('password', $body);
        $this->assertStringNotContainsString('remember_token', $body);
        $this->assertStringNotContainsString('ver_code', $body);
    }

    // ───────────────────────────── LOGOUT (DUC-AUTH-LOGOUT) ─────────────────────────────

    /** @test AC1: logout deletes token */
    public function logout_revokes_current_token(): void
    {
        $user = $this->activeUser();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(204);

        // AC2: token must no longer authenticate
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me')
            ->assertStatus(401);
    }

    /** @test AC3: other tokens of same user remain valid */
    public function logout_does_not_revoke_other_tokens_of_same_user(): void
    {
        $user = $this->activeUser();
        $tokenA = $user->createToken('device-a')->plainTextToken;
        $tokenB = $user->createToken('device-b')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->postJson('/api/v1/auth/logout')
            ->assertStatus(204);

        $this->withHeader('Authorization', 'Bearer ' . $tokenB)
            ->getJson('/api/v1/auth/me')
            ->assertOk();
    }

    /** @test AC4: logout without token */
    public function logout_without_token_returns_401(): void
    {
        $this->postJson('/api/v1/auth/logout')->assertStatus(401);
    }
}
