<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = auth()->user();
            
            // Check if user account is active
            if (!$user->status) {
                if ($request->is('api/*')) {
                    $notify[] = 'Your account is suspended.';
                    return response()->json([
                        'remark'  => 'suspended',
                        'status'  => 'error',
                        'message' => ['error' => $notify],
                        'data'    => [
                            'user' => $user
                        ],
                    ]);
            } else {
                    return to_route('user.authorization');
                }
            }
            
            // Check email verification only if enabled in settings
            $emailVerificationRequired = gs('ev') && !$user->ev;
            
            // Check mobile verification only if enabled in settings  
            $mobileVerificationRequired = gs('sv') && !$user->sv;
            
            if ($emailVerificationRequired || $mobileVerificationRequired) {
                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return response()->json([
                        'remark'  => 'unverified',
                        'status'  => 'error',
                        'message' => ['error' => $notify],
                        'data'    => [
                            'user' => $user
                        ],
                    ]);
                } else {
                    return to_route('user.authorization');
                }
            }
            
            return $next($request);
        }
        abort(403);
    }
}
