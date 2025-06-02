<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\CompanyWalletController;
Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


// Route::controller('TicketController')->prefix('ticket')->name(value: 'ticket.')->group(callback: function () {
//     Route::get('/',  'supportTicket')->name('index');
//     Route::get('new', 'openSupportTicket')->name('open');
//     Route::post('create',  'storeSupportTicket')->name('store');
//     Route::get('view/{ticket}', 'viewTicket')->name('view');
//     Route::post('reply/{id}',  'replyTicket')->name('reply');
//     Route::post('close/{id}',  'closeTicket')->name('close');
//     Route::get('download/{attachment_id}',  'ticketDownload')->name('download');
// });



// Company Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::get('rating/{slug}', action: [SiteController::class, 'companyRating'])->name('rating');
    Route::get('all', action: [SiteController::class, 'companies'])->name('all');
    Route::get('/', action: [SiteController::class, 'searchFromBanner'])->name('search');
    Route::get('category/{id}/{slug}', action: [SiteController::class, 'categoryCompany'])->name('category');
    Route::get('filter', action: [SiteController::class, 'filterCompanies'])->name('filter');
    Route::get('{id}/{slug}', action: [SiteController::class, 'companyDetails'])->name('details');
    
    Route::middleware('check.status')->group(function () {
        Route::post('review/{id}', action: [SiteController::class, 'review'])->name('user.review');
    //     Route::get('get/review/{id}', action: [SiteController::class, 'getReview'])->name('get.user.review');
    });
});

//review
// Route::controller(controller: 'SiteController')->name('rating.')->prefix('rating')->group(function () {
//     Route::get('/rating/{rating_id}/reactions',  'getReactionsByRating')->name('rating.reactions');
// });


Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('blog', 'blogs')->name('blog');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    Route::get('companies/{company}/{slug}', 'companyDetails')->name('company.details');
    
    // Add contractor search routes
    Route::get('contractors/search', 'contractorSearch')->name('contractors.search');
    Route::get('contractors/category/{category}', 'contractorsByCategory')->name('companies.category');
    Route::get('contractors/{id}', 'contractorProfile')->name('contractors.profile');

    Route::get('/', 'index')->name('home');
    Route::get('reactions/rating/{rating_id}',  'getReactionsByRating')->name('reactions.rating');
    Route::get('/about/doitay', 'about')->name('about');
    Route::post('add-click/{id}', 'addClick')->name('add.click');
    
    // Become Contractor Landing Page
    Route::get('become-contractor', 'becomeContractor')->name('become.contractor');
    Route::post('become-contractor/register', 'becomeContractorRegister')->name('become.contractor.register');
    
    // Pages route should be last to avoid conflicts
    Route::get('{slug}', 'pages')->name('pages');
});

Route::controller('LocationController')->prefix('localtion')->name('localtion.')->group(function () {
    Route::get('/api/cities', 'getCities')->name('cities');
    Route::get('/api/districts/{city_code}',  'getDistricts')->name('districts');
    Route::get('/api/wards/{district_code}',  'getWards')->name('wards');
});

// User Support Ticket
Route::prefix('ticket')->group(callback: function () {
    Route::get('/', action: [TicketController::class, 'supportTicket'])->name('ticket.index');
    Route::get('new', action: [TicketController::class, 'openSupportTicket'])->name('ticket.open');
    Route::post('create', action: [TicketController::class, 'storeSupportTicket'])->name('ticket.store');
    Route::get('view/{ticket}', action: [TicketController::class, 'viewTicket'])->name('ticket.view');
    Route::post('reply/{id}', action: [TicketController::class, 'replyTicket'])->name('ticket.reply');
    Route::post('close/{id}', action: [TicketController::class, 'closeTicket'])->name('ticket.close');
    Route::get('download/{attachment_id}', action: [TicketController::class, 'ticketDownload'])->name('ticket.download');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing admin routes ...

    // Company Wallet Routes
    Route::resource('company-wallets', CompanyWalletController::class);
    Route::post('company-wallets/{company_wallet}/add-funds', [CompanyWalletController::class, 'addFunds'])->name('company-wallets.add-funds');
    Route::post('company-wallets/{company_wallet}/deduct-funds', [CompanyWalletController::class, 'deductFunds'])->name('company-wallets.deduct-funds');
});

// // User routes
// Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
//     Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
// });

Route::get('/localtion/api/districts-by-name/{city}', function($city) {
    return \DB::table('vietnam_districts')
        ->select('district')
        ->where('city', $city)
        ->groupBy('district')
        ->orderBy('district')
        ->get();
});
Route::get('/localtion/api/wards-by-name/{district}', function($district) {
    return \DB::table('vietnam_districts')
        ->select('ward')
        ->where('district', $district)
        ->groupBy('ward')
        ->orderBy('ward')
        ->get();
});

// Test auth route
Route::get('/test-auth', function() {
    if (auth()->check()) {
        $user = auth()->user();
        return "Logged in as: " . $user->fullname . " (ID: " . $user->id . ")";
    } else {
        return "Not logged in";
    }
});

// Test dashboard route (thêm vào cuối file)
Route::get('/test-dashboard', function() {
    $pageTitle = 'Test Dashboard';
    $user = (object) [
        'id' => 1,
        'fullname' => 'Test User',
        'email' => 'test@example.com'
    ];
    return view('Template::user.test_dashboard', compact('pageTitle', 'user'));
});

