<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\UploadController;


Route::namespace('Auth')->group(function () {
    Route::middleware('admin.guest')->group(function(){
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('admin')->withoutMiddleware('admin.guest')->name('logout');
        });

        // Admin Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function(){
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function(){
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});

Route::middleware('admin')->group(function () {
    // Add upload route at the beginning of the admin middleware group
    Route::post('upload/image', [UploadController::class, 'uploadImage'])->name('upload.image');

    Route::controller('AdminController')->group(function(){
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('chart/deposit-withdraw', 'depositAndWithdrawReport')->name('chart.deposit.withdraw');
        Route::get('chart/transaction', 'transactionReport')->name('chart.transaction');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications','notifications')->name('notifications');
        Route::get('notification/read/{id}','notificationRead')->name('notification.read');
        Route::get('notifications/read-all','readAllNotification')->name('notifications.read.all');
        Route::post('notifications/delete-all','deleteAllNotification')->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}','deleteSingleNotification')->name('notifications.delete.single');

        //Report Bugs
        Route::get('request-report',action: 'requestReport')->name('request.report');
        Route::post('request-report','reportSubmit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });

    // == Feture ==
    Route::controller('FeatureController')->name('feature.')->prefix('feature')->group(function () {
        Route::get('/', [FeatureController::class, 'index'])->name('index');
        Route::get('/create', [FeatureController::class, 'create'])->name('create');
        Route::post('/', [FeatureController::class, 'store'])->name('store');
        Route::post('/{id}', action: [FeatureController::class, 'update'])->name('update');
    });

    //== Category ==
    Route::controller('CategoryController')->name('category.')->prefix('category')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name(name: 'store');
        Route::post('status/{id}', 'status')->name('status');
    });

    //== Company ==
    Route::controller('ManageCompanyController')->name('company.')->prefix('companies')->group(function () {
        Route::get('all/{userId?}', action: 'index')->name('index');
        Route::get('pending', 'index')->name('pending');
        Route::get('approved', 'index')->name('approved');
        Route::get('rejected', 'index')->name('rejected');

        Route::get('details/{id}', 'details')->name('details');
        Route::get('status/{id}', 'statusRedirect')->name('status.redirect');
        Route::post('status/{id}', 'status')->name('status');
    });

    //== Review ==
    Route::controller('ReviewController')->name('review.')->prefix('review')->group(function () {
        Route::get('all', 'index')->name('index');
        Route::post('delete/{id}/{slug}', 'delete')->name('delete');
    });


    // == Advertisement ===
    Route::controller('AdvertisementController')->name('advertisement.')->prefix('advertisement')->group(function () {
        Route::get('all', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id?}', 'store')->name('store');
    });

    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function(){
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('list', 'list')->name('list');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
    });



    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function(){
        Route::get('transaction/{user_id?}', 'transaction')->name('transaction');
        Route::get('login/history', 'loginHistory')->name('login.history');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory');
        Route::get('notification/history', 'notificationHistory')->name('notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');
    });


    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->group(function(){
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });


    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function(){
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    Route::controller('GeneralSettingController')->group(function(){

        Route::get('system-setting', 'systemSetting')->name('setting.system');

        // General Setting
        Route::get('general-setting', 'general')->name('setting.general');
        Route::post('general-setting', 'generalUpdate');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration','systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration','systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','customCss')->name('setting.custom.css');
        Route::post('custom-css','customCssSubmit');

        //Custom CSS
        Route::get('sitemap','sitemap')->name('setting.sitemap');
        Route::post('sitemap','sitemapSubmit');

        //Custom CSS
        Route::get('robot','robot')->name('setting.robot');
        Route::post('robot','robotSubmit');

        //Cookie
        Route::get('cookie','cookie')->name('setting.cookie');
        Route::post('cookie','cookieSubmit');

        //maintenance_mode
        Route::get('maintenance-mode','maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode','maintenanceModeSubmit');

    });


    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function(){
        //Template Setting
        Route::get('global/email','globalEmail')->name('global.email');
        Route::post('global/email/update','globalEmailUpdate')->name('global.email.update');

        Route::get('global/sms','globalSms')->name('global.sms');
        Route::post('global/sms/update','globalSmsUpdate')->name('global.sms.update');

        Route::get('global/push','globalPush')->name('global.push');
        Route::post('global/push/update','globalPushUpdate')->name('global.push.update');

        Route::get('templates','templates')->name('templates');
        Route::get('template/edit/{type}/{id}','templateEdit')->name('template.edit');
        Route::post('template/update/{type}/{id}','templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting','emailSetting')->name('email');
        Route::post('email/setting','emailSettingUpdate');
        Route::post('email/test','emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting','smsSetting')->name('sms');
        Route::post('sms/setting','smsSettingUpdate');
        Route::post('sms/test','smsTest')->name('sms.test');

        Route::get('notification/push/setting', 'pushSetting')->name('push');
        Route::post('notification/push/setting', 'pushSettingUpdate');
    });

    // Plugin
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });

    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function(){
        Route::get('info','systemInfo')->name('info');
        Route::get('server-info','systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
        Route::get('system-update','systemUpdate')->name('update');
        Route::post('system-update','systemUpdateProcess')->name('update.process');
        Route::get('system-update/log','systemUpdateLog')->name('update.log');
    });

    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function(){
            Route::get('index', 'index')->name('index');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
            Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
            Route::post('frontend-element-seo/{key}/{id}', 'frontendSeoUpdate');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller(controller: 'PageBuilderController')->group(function(){
            Route::get('manage-pages', action: [PageBuilderController::class, 'managePages'])->name('manage.pages');
            Route::get('manage-pages/check-slug/{id?}', action: [PageBuilderController::class, 'checkSlug'])->name('manage.pages.check.slug');
            Route::post('manage-pages', action: [PageBuilderController::class,'managePagesSave'])->name('manage.pages.save');
            Route::post('manage-pages/update', action: [PageBuilderController::class,'managePagesUpdate'])->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', action: [PageBuilderController::class,'managePagesDelete'])->name('manage.pages.delete');
            Route::get('manage-section/{id}', action: [PageBuilderController::class,'manageSection'])->name('manage.section');
            Route::post('manage-section/{id}', action: [PageBuilderController::class,'manageSectionUpdate'])->name('manage.section.update');
            Route::get('manage-seo/{id}', action: [PageBuilderController::class,'manageSeo'])->name('manage.pages.seo');
            Route::post('manage-seo/{id}',action: [PageBuilderController::class,'manageSeoStore']);
        });
    });
});
