<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * JSON variant of RegistrationStep — forces `profile_complete = 1` before
 * protected endpoints become usable.
 *
 * Frontend handles the redirect to /vi/hoan-thanh-ho-so on 409 `profile_incomplete`.
 */
class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! (int) $user->profile_complete) {
            return response()->json([
                'message' => __('Vui lòng hoàn thành hồ sơ trước khi sử dụng tính năng này'),
                'reason'  => 'profile_incomplete',
            ], 409);
        }

        return $next($request);
    }
}
