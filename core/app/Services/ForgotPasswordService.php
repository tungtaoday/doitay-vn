<?php

namespace App\Services;

use App\Exceptions\Api\InvalidCredentialsException;
use App\Models\User;
use App\Support\Identifier;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * Forgot-password flow dùng mã 6 chữ số lưu vào `users.ver_code` + `ver_code_send_at`.
 *
 * Gửi qua email (nếu identifier là email) hoặc SMS (nếu phone). SMS gateway
 * thực tế sẽ cần cấu hình Phase 2 — hiện tại chỉ log mã ra để dev xem.
 */
class ForgotPasswordService
{
    private const CODE_TTL_MINUTES = 15;

    public function sendResetCode(string $identifier): void
    {
        $type = Identifier::detect($identifier);
        if ($type === null) {
            throw new \InvalidArgumentException('invalid identifier');
        }

        $user = $type === Identifier::TYPE_EMAIL
            ? User::where('email', strtolower($identifier))->first()
            : User::where('mobile', Identifier::normalizePhone($identifier))->first();

        if (! $user) {
            return; // im lặng để tránh enumeration
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->ver_code = $code;
        $user->ver_code_send_at = now();
        $user->save();

        if ($type === Identifier::TYPE_EMAIL) {
            try {
                Mail::raw(
                    __('Mã đặt lại mật khẩu doitay.vn: :code (hết hạn sau :m phút)', [
                        'code' => $code,
                        'm'    => self::CODE_TTL_MINUTES,
                    ]),
                    fn ($m) => $m->to($user->email)->subject('doitay.vn — Mã đặt lại mật khẩu'),
                );
            } catch (\Throwable $e) {
                Log::warning('forgot-password mail failed', ['e' => $e->getMessage()]);
            }
        } else {
            Log::info('forgot-password sms (stub)', [
                'mobile' => $user->mobile,
                'code'   => $code,
            ]);
        }
    }

    public function resetPassword(string $identifier, string $code, string $newPassword): void
    {
        $type = Identifier::detect($identifier);
        if ($type === null) {
            throw new InvalidCredentialsException();
        }

        $user = $type === Identifier::TYPE_EMAIL
            ? User::where('email', strtolower($identifier))->first()
            : User::where('mobile', Identifier::normalizePhone($identifier))->first();

        if (! $user || ! $user->ver_code || ! hash_equals((string) $user->ver_code, $code)) {
            throw new InvalidCredentialsException();
        }

        $sentAt = $user->ver_code_send_at;
        if (! $sentAt || now()->diffInMinutes($sentAt) > self::CODE_TTL_MINUTES) {
            throw new InvalidCredentialsException();
        }

        $user->password = Hash::make($newPassword);
        $user->ver_code = null;
        $user->ver_code_send_at = null;
        $user->save();
    }
}
