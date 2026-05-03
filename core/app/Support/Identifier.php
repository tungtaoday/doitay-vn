<?php

namespace App\Support;

/**
 * Phân biệt một chuỗi input là email hay số điện thoại Việt Nam.
 *
 * Dùng chung cho login/register/forgot-password — cho phép user nhập
 * email HOẶC SĐT trong cùng một ô "identifier".
 */
class Identifier
{
    public const TYPE_EMAIL = 'email';
    public const TYPE_PHONE = 'phone';

    public static function detect(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return self::TYPE_EMAIL;
        }
        if (self::isVietnamesePhone($value)) {
            return self::TYPE_PHONE;
        }
        return null;
    }

    public static function normalizePhone(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';
        if (str_starts_with($digits, '84') && strlen($digits) >= 11) {
            return '0' . substr($digits, 2);
        }
        return $digits;
    }

    private static function isVietnamesePhone(string $value): bool
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';
        $len = strlen($digits);
        if ($len < 9 || $len > 12) {
            return false;
        }
        $normalized = self::normalizePhone($value);
        return (bool) preg_match('/^0\d{9,10}$/', $normalized);
    }
}
