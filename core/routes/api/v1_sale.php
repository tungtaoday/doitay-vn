<?php

use App\Http\Controllers\API\V1\Sale\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — Sale namespace (authenticated CTV)
|--------------------------------------------------------------------------
| Mounted at /api/v1/sale under auth:sanctum.
|
| Phase 1: CTV nhập hồ sơ thợ + xem danh sách của mình (chống trùng SĐT).
| TODO Phase 1.5: thêm middleware role `ctv` khi hệ role sẵn sàng
|                 (hiện gate bằng auth:sanctum — CTV là user được cấp tài khoản).
| Ref: BUC-SALE-CTV-ONBOARDING, DUC-SUBMISSION-CREATE / LIST.
*/

Route::post('submissions', [SubmissionController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('submissions.store');

Route::get('submissions', [SubmissionController::class, 'index'])
    ->name('submissions.index');

// Tra SĐT trước khi nộp: thợ đã tự mở hồ sơ chưa, đã có CTV nhận công chưa.
Route::get('tho-lookup', [SubmissionController::class, 'lookup'])
    ->middleware('throttle:60,1')
    ->name('tho-lookup');
