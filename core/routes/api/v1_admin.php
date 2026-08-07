<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Admin\SeedController;
use App\Http\Controllers\API\V1\Admin\SubmissionReviewController;

/*
|--------------------------------------------------------------------------
| API v1 — Admin namespace
|--------------------------------------------------------------------------
|
| Loaded from routes/api.php under prefix /api/v1/admin, behind auth:sanctum.
|
| TODO Phase 3: implement an API-friendly admin guard middleware that
| returns JSON 403 instead of redirecting to login (RedirectIfNotAdmin
| currently redirects, which is wrong for an API context).
*/

// ── Seed marketplace data (AI agent automation) ──────────────────────────
Route::prefix('seed')->name('seed.')->group(function () {
    Route::get('stats',                         [SeedController::class, 'stats'])->name('stats');
    Route::post('run',                          [SeedController::class, 'run'])->name('run');
    Route::post('users',                        [SeedController::class, 'createUser'])->name('users.create');
    Route::post('users/{id}/avatar',            [SeedController::class, 'uploadAvatar'])->name('users.avatar');
    Route::post('companies',                    [SeedController::class, 'createCompany'])->name('companies.create');
    Route::post('companies/{id}/image',         [SeedController::class, 'uploadCompanyImage'])->name('companies.image');
    Route::post('appointments',                 [SeedController::class, 'createAppointment'])->name('appointments.create');
    Route::get('companies',                     [SeedController::class, 'listCompanies'])->name('companies.list');
    Route::get('customers',                     [SeedController::class, 'listCustomers'])->name('customers.list');
    Route::delete('flush',                      [SeedController::class, 'flushAll'])->name('flush');
});

// ── Sale submissions review (Quản lý duyệt hồ sơ thợ) ─────────────────────
Route::get('submissions',                [SubmissionReviewController::class, 'index'])->name('submissions.index');
Route::patch('submissions/{id}/approve', [SubmissionReviewController::class, 'approve'])->whereNumber('id')->name('submissions.approve');
Route::patch('submissions/{id}/reject',  [SubmissionReviewController::class, 'reject'])->whereNumber('id')->name('submissions.reject');

// ── Vận hành (P0.3): hàng đợi chờ xử lý + duyệt thợ tự đăng ký (P1.3) ──────
Route::get('ops/queues',                 [SubmissionReviewController::class, 'queues'])->name('ops.queues');
Route::patch('companies/{id}/approve',   [SubmissionReviewController::class, 'approveCompany'])->whereNumber('id')->name('companies.approve');

// ── Trung tâm điều hành /quan-tri: phễu + chi phí + hiệu suất CTV ──────────
Route::get('dashboard/overview',   [\App\Http\Controllers\API\V1\Admin\AdminDashboardController::class, 'overview'])->name('dashboard.overview');
Route::get('ctv/performance',      [\App\Http\Controllers\API\V1\Admin\AdminDashboardController::class, 'ctvPerformance'])->name('ctv.performance');
Route::patch('commissions/mark-paid', [\App\Http\Controllers\API\V1\Admin\AdminDashboardController::class, 'markPaid'])->name('commissions.mark-paid');

// ── Ba mặt quản trị còn thiếu: khách hàng · điểm chạm · việc hôm nay ───────
Route::prefix('insight')->name('insight.')->group(function () {
    Route::get('khach-hang', [\App\Http\Controllers\API\V1\Admin\InsightController::class, 'khachHang'])->name('khach-hang');
    Route::get('diem-cham',  [\App\Http\Controllers\API\V1\Admin\InsightController::class, 'diemCham'])->name('diem-cham');
    Route::get('hom-nay',    [\App\Http\Controllers\API\V1\Admin\InsightController::class, 'viecHomNay'])->name('hom-nay');
});
