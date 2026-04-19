<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * JSON variant of CheckStatus — blocks banned users and users requiring
 * email/SMS verification (gated by gs('ev') / gs('sv')).
 *
 * For Blade → see App\Http\Middleware\CheckStatus.
 */
class EnsureUserActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => __('Chưa đăng nhập')], 401);
        }

        if ((int) $user->status !== 1) {
            return response()->json([
                'message' => __('Tài khoản đã bị khoá'),
                'reason'  => 'banned',
            ], 403);
        }

        if (function_exists('gs')) {
            if (gs('ev') && ! $user->ev) {
                return response()->json([
                    'message' => __('Vui lòng xác thực email trước khi tiếp tục'),
                    'reason'  => 'email_unverified',
                ], 403);
            }

            if (gs('sv') && ! $user->sv) {
                return response()->json([
                    'message' => __('Vui lòng xác thực số điện thoại trước khi tiếp tục'),
                    'reason'  => 'mobile_unverified',
                ], 403);
            }
        }

        return $next($request);
    }
}
