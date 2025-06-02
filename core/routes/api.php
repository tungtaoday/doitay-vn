<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyWalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // ... existing routes ...

    // Company Wallet Routes
    Route::apiResource('company-wallets', CompanyWalletController::class);
    Route::post('company-wallets/{company_wallet}/add-funds', [CompanyWalletController::class, 'addFunds']);
    Route::post('company-wallets/{company_wallet}/deduct-funds', [CompanyWalletController::class, 'deductFunds']);
    Route::get('company-wallets/{company_wallet}/balance', [CompanyWalletController::class, 'getBalance']);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware'),
    'verified'
])->group(function () {
    // ... existing routes ...
}); 