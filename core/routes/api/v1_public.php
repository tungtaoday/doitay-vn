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

// SĐT liên hệ trực tiếp — CHỈ cho Zalo Mini App (gọi/nhắn ngay). Web vẫn ẩn SĐT.
Route::get('companies/{id}/contact', [CompanyController::class, 'contact'])
    ->whereNumber('id')->middleware('throttle:30,1')->name('companies.contact');

Route::get('companies/{id}/ratings', [RatingController::class, 'index'])->whereNumber('id')->name('companies.ratings');
Route::get('categories/{id}/features', [RatingController::class, 'features'])->whereNumber('id')->name('categories.features');

// SEO: cac cap nghe x khu vuc co du cung tho (nguon cho landing + sitemap).
Route::get('service-areas', [\App\Http\Controllers\API\V1\Public\ServiceAreaController::class, 'index'])->name('service-areas.index');

// ── Hồ sơ thợ từ Zalo Mini App — SERVER là nguồn sự thật ──
// store  : tạo/cập nhật hồ sơ (thợ tự làm)
// me     : khôi phục hồ sơ khi đổi máy / cài lại app
// claim  : thợ bấm link CTV gửi để nhận hồ sơ được dựng hộ
// images : tải ảnh việc lên server (không còn base64 trong máy thợ)
Route::post('tho-profiles', [\App\Http\Controllers\API\V1\Public\ThoProfileController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('tho-profiles.store');

Route::get('tho-profiles/me', [\App\Http\Controllers\API\V1\Public\ThoProfileController::class, 'me'])
    ->middleware('throttle:30,1')
    ->name('tho-profiles.me');

Route::post('tho-profiles/{id}/claim', [\App\Http\Controllers\API\V1\Public\ThoProfileController::class, 'claim'])
    ->whereNumber('id')
    ->middleware('throttle:10,1')
    ->name('tho-profiles.claim');

Route::post('tho-profiles/{id}/images', [\App\Http\Controllers\API\V1\Public\ThoProfileController::class, 'uploadImages'])
    ->whereNumber('id')
    ->middleware('throttle:20,1')
    ->name('tho-profiles.images');

// ── Đặt lịch KHÔNG cần đăng nhập (guest) — tự tạo tài khoản theo SĐT/email ──
Route::post('guest-appointments', [GuestAppointmentController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('guest-appointments.store');

// ── Product events — đo phễu BẮC ĐẨU (thợ share → khách liên hệ) ──
// Ghi event từ Mini App (thợ) + web (khách). Public, rate-limit chống spam.
Route::post('events', [EventController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('events.store');

// -- Nhat lenh: bot blueprint day viec trong ngay len cho trung tam quan tri --
// Chan bang METRICS_TOKEN vi ben ghi la cron, khong co phien dang nhap.
Route::post('nhat-lenh', [\App\Http\Controllers\API\V1\Public\NhatLenhController::class, 'store'])
    ->name('nhat-lenh.store');

// Thống kê Bắc Đẩu — guard bằng METRICS_TOKEN (?token=... hoặc Bearer).
Route::get('metrics/bac-dau', [MetricsController::class, 'bacDau'])
    ->middleware('throttle:30,1')
    ->name('metrics.bac-dau');

// Hiệu suất TỪNG thợ (bảng quản lý): ai chưa nhận hồ sơ, chưa share, share mà không ai gọi.
Route::get('metrics/tho-performance', [MetricsController::class, 'thoPerformance'])
    ->middleware('throttle:30,1')
    ->name('metrics.tho-performance');
