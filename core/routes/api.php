<?php

use App\Http\Controllers\API\LeadMatchingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Headless rebuild — Phase 1.
|
| Versioned API lives under /api/v1, split into 3 namespaces matching the
| existing controller layout (Admin / User / API):
|
|   /api/v1/public/*  → Api/V1/Public/*  (no auth)
|   /api/v1/user/*    → Api/V1/User/*    (auth:sanctum)
|   /api/v1/admin/*   → Api/V1/Admin/*   (auth:sanctum + admin)
|
| Auth endpoints (login/register/me/logout) live at /api/v1/auth/*.
|
| Legacy endpoints below /api/v1 are kept for backwards compatibility with
| the existing Blade frontend until cutover is complete.
*/

// ─────────────────────────── Versioned v1 ───────────────────────────

Route::prefix('v1')->name('api.v1.')->group(function () {

    // Auth (shared)
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('register', [\App\Http\Controllers\API\V1\AuthController::class, 'register'])
            ->middleware('throttle:20,1')
            ->name('register');

        Route::post('login', [\App\Http\Controllers\API\V1\AuthController::class, 'login'])
            ->middleware('throttle:20,1')
            ->name('login');

        Route::post('social/google', [\App\Http\Controllers\API\V1\AuthController::class, 'socialGoogle'])
            ->middleware('throttle:10,1')
            ->name('social.google');

        Route::post('social/facebook', [\App\Http\Controllers\API\V1\AuthController::class, 'socialFacebook'])
            ->middleware('throttle:10,1')
            ->name('social.facebook');

        Route::post('password/forgot', [\App\Http\Controllers\API\V1\AuthController::class, 'forgotPassword'])
            ->middleware('throttle:3,1')
            ->name('password.forgot');

        Route::post('password/reset', [\App\Http\Controllers\API\V1\AuthController::class, 'resetPassword'])
            ->middleware('throttle:5,1')
            ->name('password.reset');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me',      [\App\Http\Controllers\API\V1\AuthController::class, 'me'])->name('me');
            Route::post('logout', [\App\Http\Controllers\API\V1\AuthController::class, 'logout'])->name('logout');
        });
    });

    // Public namespace (no auth)
    Route::prefix('public')
        ->name('public.')
        ->middleware('throttle:60,1')
        ->group(base_path('routes/api/v1_public.php'));

    // User namespace (auth:sanctum)
    Route::prefix('user')
        ->name('user.')
        ->middleware(['auth:sanctum'])
        ->group(base_path('routes/api/v1_user.php'));

    // Admin namespace (auth:sanctum + admin guard)
    // NOTE: 'admin' middleware below is the existing RedirectIfNotAdmin alias.
    // It will need a JSON variant for API context — tracked as TODO Phase 3.
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['auth:sanctum'])
        ->group(base_path('routes/api/v1_admin.php'));
});

// ─────────────────────────── Legacy (untouched) ───────────────────────────

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Lead Matching API (existing)
Route::post('/find-matching-contractors', [LeadMatchingController::class, 'findMatchingContractors']);
Route::get('/categories/{categoryId}/features', [LeadMatchingController::class, 'getCategoryFeatures']);
