<?php

use App\Http\Controllers\API\V1\Public\CategoryController;
use App\Http\Controllers\API\V1\Public\CompanyController;
use App\Http\Controllers\API\V1\Public\DepositMethodController;
use App\Http\Controllers\API\V1\Public\LocationController;
use App\Http\Controllers\API\V1\Public\RatingController;
use App\Http\Controllers\API\V1\Public\EventController;
use App\Http\Controllers\API\V1\Public\GuestAppointmentController;
use App\Http\Controllers\API\V1\Public\MetricsController;
use App\Http\Controllers\API\V1\Public\SiteSettingsController;
use App\Http\Controllers\API\V1\Public\StatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — Public namespace
|--------------------------------------------------------------------------
|
| Loaded from routes/api.php under prefix /api/v1/public, no auth.
| Used by the new Next.js frontend for browsable, indexable content.
*/

Route::get('site-settings',     [SiteSettingsController::class, 'show'])->name('site-settings.show');
Route::get('stats',             [StatsController::class, 'show'])->name('stats.show');
Route::get('deposit-methods',   [DepositMethodController::class, 'index'])->name('deposit-methods.index');
Route::get('locations/cities',                [LocationController::class, 'cities'])->name('locations.cities');
Route::get('locations/districts/{cityCode}',  [LocationController::class, 'districts'])->name('locations.districts');
Route::get('locations/wards/{districtCode}',  [LocationController::class, 'wards'])->name('locations.wards');

Route::get('categories',        [CategoryController::class, 'index'])->name('categories.index');

Route::get('companies',         [CompanyController::class, 'index'])->name('companies.index');
Route::get('companies/{id}',    [CompanyController::class, 'show'])->whereNumber('id')->name('companies.show');

Route::get('companies/{id}/ratings', [RatingController::class, 'index'])->whereNumber('id')->name('companies.ratings');
Route::get('categories/{id}/features', [RatingController::class, 'features'])->whereNumber('id')->name('categories.features');

// SEO: cac cap nghe x khu vuc co du cung tho (nguon cho landing + sitemap).
Route::get('service-areas', [\App\Http\Controllers\API\V1\Public\ServiceAreaController::class, 'index'])->name('service-areas.index');

// ── Xuất bản hồ sơ thợ từ Zalo Mini App (self-serve) — rate-limit chống spam ──
Route::post('tho-profiles', [\App\Http\Controllers\API\V1\Public\ThoProfileController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('tho-profiles.store');

// ── Đặt lịch KHÔNG cần đăng nhập (guest) — tự tạo tài khoản theo SĐT/email ──
Route::post('guest-appointments', [GuestAppointmentController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('guest-appointments.store');

// ── Product events — đo phễu BẮC ĐẨU (thợ share → khách liên hệ) ──
// Ghi event từ Mini App (thợ) + web (khách). Public, rate-limit chống spam.
Route::post('events', [EventController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('events.store');

// Thống kê Bắc Đẩu — guard bằng METRICS_TOKEN (?token=... hoặc Bearer).
Route::get('metrics/bac-dau', [MetricsController::class, 'bacDau'])
    ->middleware('throttle:30,1')
    ->name('metrics.bac-dau');
