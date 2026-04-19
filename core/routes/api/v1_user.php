<?php

use App\Http\Controllers\API\V1\User\AppointmentController;
use App\Http\Controllers\API\V1\User\DepositController;
use App\Http\Controllers\API\V1\User\NotificationController;
use App\Http\Controllers\API\V1\User\ProfileController;
use App\Http\Controllers\API\V1\User\ServiceRequestController;
use App\Http\Controllers\API\V1\User\ThoAppointmentController;
use App\Http\Controllers\API\V1\User\UserCompanyController;
use App\Http\Controllers\API\V1\User\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — User namespace (authenticated)
|--------------------------------------------------------------------------
| Mounted at /api/v1/user under auth:sanctum.
|
| Middleware chain for gated endpoints:
|   auth:sanctum (parent) → api.active → api.profile
|
| `complete-profile` is the ONLY endpoint that runs with api.active but
| WITHOUT api.profile — it's the exit door for users with profile_complete=0.
*/

// Profile completion — gated by active only (not profile_complete, obviously)
Route::post('complete-profile', [ProfileController::class, 'completeProfile'])
    ->middleware(['api.active', 'throttle:10,1'])
    ->name('profile.complete');

// Profile update — gated by active + profile_complete
Route::put('profile', [ProfileController::class, 'updateProfile'])
    ->middleware(['api.active', 'api.profile', 'throttle:20,1'])
    ->name('profile.update');

// Avatar upload
Route::post('avatar', [ProfileController::class, 'uploadAvatar'])
    ->middleware(['api.active', 'throttle:10,1'])
    ->name('profile.avatar');

// All other user endpoints require both active + profile complete
Route::middleware(['api.active', 'api.profile'])->group(function () {

    // --- Notifications (in-app) ---
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])
        ->middleware('throttle:30,1')
        ->name('notifications.read-all');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])
        ->whereNumber('id')
        ->middleware('throttle:60,1')
        ->name('notifications.read');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])
        ->whereNumber('id')
        ->middleware('throttle:30,1')
        ->name('notifications.destroy');

    // --- My companies (thợ registration) ---
    Route::get('companies', [UserCompanyController::class, 'index'])->name('companies.index');
    Route::post('companies', [UserCompanyController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('companies.store');
    Route::put('companies/{id}', [UserCompanyController::class, 'update'])
        ->whereNumber('id')
        ->middleware('throttle:20,1')
        ->name('companies.update');
    Route::post('companies/{id}/image', [UserCompanyController::class, 'uploadImage'])
        ->whereNumber('id')
        ->middleware('throttle:10,1')
        ->name('companies.upload-image');
    Route::post('companies/{id}/portfolio', [UserCompanyController::class, 'uploadPortfolio'])
        ->whereNumber('id')
        ->middleware('throttle:20,1')
        ->name('companies.upload-portfolio');

    Route::get('wallet', [WalletController::class, 'overview'])->name('wallet.overview');
    Route::get('wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');

    Route::get('deposits', [DepositController::class, 'index'])->name('deposits.index');
    Route::get('deposits/{id}', [DepositController::class, 'show'])->whereNumber('id')->name('deposits.show');
    Route::post('deposits', [DepositController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('deposits.store');
    Route::post('deposits/{id}/upload-proof', [DepositController::class, 'uploadProof'])
        ->whereNumber('id')
        ->middleware('throttle:10,1')
        ->name('deposits.upload-proof');
    Route::post('deposits/{id}/cancel', [DepositController::class, 'cancel'])
        ->whereNumber('id')
        ->middleware('throttle:10,1')
        ->name('deposits.cancel');

    // --- Service requests (broadcast + matching, Thumbtack-style) ---
    Route::get('service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::post('service-requests', [ServiceRequestController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('service-requests.store');
    Route::get('service-requests/{id}', [ServiceRequestController::class, 'show'])
        ->whereNumber('id')
        ->name('service-requests.show');

    // --- Appointments (customer side) ---
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('appointments', [AppointmentController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('appointments.store');
    Route::get('appointments/{id}', [AppointmentController::class, 'show'])->whereNumber('id')->name('appointments.show');
    Route::post('appointments/{id}/cancel', [AppointmentController::class, 'cancel'])
        ->whereNumber('id')
        ->middleware('throttle:10,1')
        ->name('appointments.cancel');
    Route::post('appointments/{id}/review', [AppointmentController::class, 'submitReview'])
        ->whereNumber('id')
        ->middleware('throttle:5,1')
        ->name('appointments.review');
    Route::get('appointments/{id}/rating', [AppointmentController::class, 'rating'])
        ->whereNumber('id')
        ->name('appointments.rating');

    // --- Thợ (company side) — "tho" prefix ---
    Route::prefix('tho')->name('tho.')->group(function () {
        Route::get('appointments', [ThoAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/{id}', [ThoAppointmentController::class, 'show'])->whereNumber('id')->name('appointments.show');
        Route::post('appointments/{id}/confirm', [ThoAppointmentController::class, 'confirm'])
            ->whereNumber('id')
            ->middleware('throttle:10,1')
            ->name('appointments.confirm');
        Route::post('appointments/{id}/complete', [ThoAppointmentController::class, 'complete'])
            ->whereNumber('id')
            ->middleware('throttle:10,1')
            ->name('appointments.complete');
        Route::post('appointments/{id}/cancel', [ThoAppointmentController::class, 'cancel'])
            ->whereNumber('id')
            ->middleware('throttle:10,1')
            ->name('appointments.cancel');
    });
});
