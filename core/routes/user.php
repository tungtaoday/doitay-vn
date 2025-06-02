<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\User\CompanyAppointmentController;
use App\Http\Controllers\User\CompanyStatisticsController;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/login', 'showLoginForm')->name('login');
            Route::post('/login', 'login');
            Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
        });

        Route::controller('RegisterController')->middleware(['guest'])->group(function () {
            Route::get('register', 'showRegistrationForm')->name('register');
            Route::post('register', 'register');
            Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
        });

        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('request');
            Route::post('email', 'sendResetCodeEmail')->name('email');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function () {
            Route::post('password/reset', 'reset')->name('password.update');
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
        });

        Route::controller('SocialiteController')->group(function () {
            Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
            Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
        });
    });
});

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');
    Route::get('get-districts', 'User\CompanyController@getDistricts')->name('get.districts');
    Route::get('get-wards', 'User\CompanyController@getWards')->name('get.wards');
    
    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {
        Route::namespace('User')->group(function () {
            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');


                //Report
                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            //Company-Manage
            Route::controller('CompanyController')->prefix('company')->name('company.')->group(function () {
                Route::get('all', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('update/{id}', 'store')->name('update');
            });

            // Customer Lead Management
            Route::controller('CustomerLeadController')->prefix('customer/leads')->name('customer.leads.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('show/{id}', 'show')->name('show');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::put('update/{id}', 'update')->name('update');
                Route::post('close/{id}', 'close')->name('close');
                Route::post('select-contractor/{id}', 'selectContractor')->name('select-contractor');
            });

            // Lead Management for Contractors
            Route::controller('LeadController')->prefix('leads')->name('leads.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('dashboard', 'dashboard')->name('dashboard');
                Route::get('show/{id}', 'show')->name('show');
                Route::post('purchase/{id}', 'purchase')->name('purchase');
                Route::get('my-purchases', 'myPurchases')->name('my-purchases');
                Route::post('update-status/{id}', 'updateStatus')->name('update-status');
            });

            // Wallet Management
            Route::controller('WalletController')->prefix('wallet')->name('wallet.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('dashboard', 'dashboard')->name('dashboard');
                Route::get('show/{id}', 'show')->name('show');
                Route::get('transactions', 'transactions')->name('transactions');
                Route::post('create', 'createWallet')->name('create');
            });

            // Review-update
            Route::controller('UserController')->prefix('review')->name('review.')->group(function () {
                Route::post('update', 'updateReview')->name('update');
                Route::post('delete', 'deleteReview')->name('delete');
            });

            Route::controller('UserController')->prefix('reaction')->name('reaction.')->group(function () {
                Route::post('/rating/{rating_id}/react/{reaction_type_id}', 'react')->name('react');
                Route::post('/rating/{rating_id}/unreact/{reaction_type_id}', 'removeReaction')->name('remove');
            });

            // Notification Management
            Route::controller('NotificationController')->prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('unread-count', 'getUnreadCount')->name('unread-count');
                Route::post('mark-as-read/{id}', 'markAsRead')->name('mark-as-read');
                Route::post('mark-all-read', 'markAllAsRead')->name('mark-all-read');
                Route::delete('delete/{id}', 'delete')->name('delete');
            });

        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/{appointmentId}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/{appointmentId}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/verify-email', [AppointmentController::class, 'verifyEmail'])->name('appointments.verifyEmail');
    Route::post('/appointments/check-email', [AppointmentController::class, 'checkEmail'])->name('appointments.checkEmail');
});

Route::group(['prefix' => 'company', 'middleware' => 'auth'], function () {
    Route::get('appointments', [CompanyAppointmentController::class, 'index'])->name('company.appointments.index');
    Route::post('appointments/{appointmentId}/confirm', [CompanyAppointmentController::class, 'confirm'])->name('company.appointments.confirm');
    Route::post('appointments/{appointmentId}/cancel', [CompanyAppointmentController::class, 'cancel'])->name('company.appointments.cancel');
    Route::post('appointments/{appointmentId}/complete', [CompanyAppointmentController::class, 'complete'])->name('company.appointments.complete');
    Route::get('appointments/{appointmentId}', [CompanyAppointmentController::class, 'show'])->name('company.appointments.show');
});

Route::get('/check-email', [AppointmentController::class, 'checkEmail']);
// Company Statistics Routes
Route::prefix('company')->name('user.company.')->group(function () {
    Route::get('statistics', [CompanyStatisticsController::class, 'index'])->name('statistics');
    Route::get('statistics/{id}', [CompanyStatisticsController::class, 'show'])->name('statistics.detail');
    Route::get('review/{company}/{review}', [CompanyStatisticsController::class, 'reviewDetail'])->name('review.detail');
});

// Routes cho việc lấy dữ liệu địa chỉ

