<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\CompanyWalletController;

// Include user routes
require __DIR__.'/user.php';

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
    Route::get('{id}/{slug}', action: [SiteController::class, 'companyDetails'])->middleware('update.company.view')->name('details');
    
    // V2 Create Company Routes (Independent Flow)
    Route::get('create-v2', action: [SiteController::class, 'createCompanyV2'])->name('create.v2');
    Route::post('store-v2', action: [SiteController::class, 'storeCompanyV2'])->name('store.v2');
    Route::get('preview-v2', action: [SiteController::class, 'previewCompanyV2'])->name('preview.v2');
    
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
    // Route::get('reactions/rating/{rating_id}',  'getReactionsByRating')->name('reactions.rating');
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

// Location API routes
Route::get('/localtion/api/cities', function() {
    return \DB::table('vietnam_districts')
        ->select('city_code', 'city')
        ->groupBy('city_code', 'city')
        ->orderBy('city')
        ->get();
});

Route::get('/localtion/api/districts/{cityCode}', function($cityCode) {
    return \DB::table('vietnam_districts')
        ->select('district_code', 'district')
        ->where('city_code', $cityCode)
        ->groupBy('district_code', 'district')
        ->orderBy('district')
        ->get();
});

Route::get('/localtion/api/wards/{districtCode}', function($districtCode) {
    return \DB::table('vietnam_districts')
        ->select('ward_code', 'ward')
        ->where('district_code', $districtCode)
        ->groupBy('ward_code', 'ward')
        ->orderBy('ward')
        ->get();
});

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

// API routes for Vietnam locations (backward compatible)
Route::prefix('api/vietnam-locations')->group(function() {
    Route::get('cities', function() {
        $cities = \DB::table('vietnam_districts')
            ->selectRaw('COALESCE(city_code, City_code) as city_code, COALESCE(city, City) as city')
            ->groupBy(\DB::raw('COALESCE(city_code, City_code), COALESCE(city, City)'))
            ->orderBy(\DB::raw('COALESCE(city, City)'))
            ->get();
        return response()->json($cities);
    });
    
    Route::get('districts/{cityCode}', function($cityCode) {
        $districts = \DB::table('vietnam_districts')
            ->selectRaw('COALESCE(district_code, District_code) as district_code, COALESCE(district, District) as district')
            ->where(function($query) use ($cityCode) {
                $query->where('city_code', $cityCode)->orWhere('City_code', $cityCode);
            })
            ->groupBy(\DB::raw('COALESCE(district_code, District_code), COALESCE(district, District)'))
            ->orderBy(\DB::raw('COALESCE(district, District)'))
            ->get();
        return response()->json($districts);
    });
    
    Route::get('wards/{districtCode}', function($districtCode) {
        $wards = \DB::table('vietnam_districts')
            ->selectRaw('COALESCE(ward_code, Ward_code) as ward_code, COALESCE(ward, Ward) as ward')
            ->where(function($query) use ($districtCode) {
                $query->where('district_code', $districtCode)->orWhere('District_code', $districtCode);
            })
            ->groupBy(\DB::raw('COALESCE(ward_code, Ward_code), COALESCE(ward, Ward)'))
            ->orderBy(\DB::raw('COALESCE(ward, Ward)'))
            ->get();
        return response()->json($wards);
    });
});

// Test database structure
Route::get('/test-db-structure', function() {
    $sample = \DB::table('vietnam_districts')->first();
    return response()->json([
        'sample_record' => $sample,
        'columns' => array_keys((array)$sample)
    ]);
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

// Test success page
Route::get('/test-success', function() {
    $appointment = \App\Models\Appointment::with('company')->first();
    if (!$appointment) {
        return 'No appointments found in database';
    }
    $pageTitle = 'Đặt lịch thành công';
    return view(activeTemplate() . 'appointment_success', compact('appointment', 'pageTitle'));
});

// Create test appointment
Route::get('/create-test-appointment', function() {
    // Get first user and company
    $user = \App\Models\User::first();
    $company = \App\Models\Company::first();
    
    if (!$user || !$company) {
        return 'Need at least 1 user and 1 company in database';
    }
    
    $appointment = \App\Models\Appointment::create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'recipient_name' => 'Test Customer',
        'recipient_phone' => '0123456789',
        'recipient_address' => 'Test Address',
        'appointment_date' => now()->addDays(1)->format('Y-m-d'),
        'appointment_time' => '10:00',
        'notes' => 'Test appointment',
        'status' => 'pending',
    ]);
    
    return 'Test appointment created with ID: ' . $appointment->id . '. <a href="/test-success">View success page</a>';
});

// ViserLab fallback routes to prevent errors
Route::get('activate', function() {
    return redirect()->route('home')->with('info', 'System activation is no longer required.');
})->name('activate');

Route::post('activate_system_submit', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'System activation has been disabled for security reasons.'
    ]);
})->name('activate_system_submit');

// Test route để kiểm tra UX mới
Route::get('/test-ux', function() {
    if (auth()->check()) {
        $user = auth()->user();
        $isContractor = $user->companies()->exists();
        
        return response()->json([
            'user_id' => $user->id,
            'username' => $user->username,
            'is_contractor' => $isContractor,
            'companies_count' => $user->companies->count(),
            'redirect_url' => $isContractor ? route('company.appointments.index') : route('appointments.index'),
            'menu_items' => $isContractor ? [
                'Quản lý lịch hẹn' => route('company.appointments.index'),
                'Quản lý ví' => route('user.wallet.index'),
                'Nhóm thợ của tôi' => route('user.company.index')
            ] : [
                'Lịch hẹn của tôi' => route('appointments.index'),
                'Trở thành Người Thợ' => route('user.company.create')
            ]
        ]);
    }
    
    return response()->json(['error' => 'Not authenticated']);
})->name('test.ux');

// Test smart lead distribution
Route::get('/test-smart-leads', function() {
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $user = auth()->user();
    $userCompanyIds = $user->companies->pluck('id');
    
    // Get visible leads
    $visibleLeads = \App\Models\LeadVisibility::whereIn('company_id', $userCompanyIds)
        ->with(['lead', 'company'])
        ->active()
        ->get();
    
    // Get company smart scores
    $companies = $user->companies->map(function($company) {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'avg_rating' => $company->getAverageRating(),
            'total_reviews' => $company->getTotalReviews(),
            'smart_score' => $company->getSmartScore(),
            'has_wallet' => $company->hasActiveWallet(),
            'wallet_balance' => $company->wallet ? $company->wallet->balance : 0
        ];
    });
    
    return response()->json([
        'user_id' => $user->id,
        'companies' => $companies,
        'visible_leads_count' => $visibleLeads->count(),
        'visible_leads' => $visibleLeads->map(function($visibility) {
            return [
                'lead_id' => $visibility->lead_id,
                'lead_title' => $visibility->lead->title,
                'priority_score' => $visibility->priority_score,
                'expires_at' => $visibility->expires_at,
                'time_remaining' => $visibility->getTimeRemaining(),
                'is_active' => $visibility->isActive()
            ];
        })
    ]);
})->name('test.smart.leads');

// Test create smart lead
Route::post('/test-create-smart-lead', function() {
    try {
        $lead = \App\Models\Lead::create([
            'customer_id' => 1, // Test customer
            'category_id' => 1, // Test category
            'title' => 'Test Smart Lead - ' . now()->format('H:i:s'),
            'description' => 'This is a test lead for smart distribution system',
            'location' => 'Quận 1, Phường Bến Nghé',
            'district' => 'Quận 1',
            'ward' => 'Phường Bến Nghé',
            'address' => ['detail' => '123 Test Street'],
            'budget_min' => 500000,
            'budget_max' => 1000000,
            'urgency' => 'medium',
            'status' => 'active',
            'needed_by' => now()->addDays(7),
            'max_contractors' => 3,
            'lead_price' => 50000,
            'expires_at' => now()->addDays(30),
        ]);
        
        // Trigger smart distribution
        $controller = new \App\Http\Controllers\User\CustomerLeadController();
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('notifyMatchingContractors');
        $method->setAccessible(true);
        $method->invoke($controller, $lead);
        
        return response()->json([
            'success' => true,
            'lead_id' => $lead->id,
            'lead_title' => $lead->title,
            'message' => 'Smart lead created and distributed successfully'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('test.create.smart.lead');

// Test email template preview route
Route::get('/email-preview/{template?}', function($template = 'NEW_APPOINTMENT') {
    $templates = [
        'NEW_APPOINTMENT' => [
            'name' => 'New Appointment Notification',
            'subject' => '🎉 Your Appointment Request Has Been Received',
            'body' => '
                <div class="greeting">Hello John Doe,</div>
                
                <div class="success-box">
                    <h3>🎉 Your Appointment Has Been Successfully Created!</h3>
                    <p>We have received your appointment request and are excited to serve you.</p>
                </div>
                
                <div class="appointment-details">
                    <h3>📅 Appointment Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Appointment ID:</span>
                        <span class="detail-value">#12345</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">January 25, 2025</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Time:</span>
                        <span class="detail-value">2:00 PM</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Service Provider:</span>
                        <span class="detail-value">ABC Cleaning Services</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value" style="color: #f39c12; font-weight: bold;">⏳ Pending Confirmation</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <h3>📋 What happens next?</h3>
                    <ul>
                        <li>The service provider will review your request</li>
                        <li>You will receive a confirmation email within 24 hours</li>
                        <li>Please prepare any required documents or information</li>
                    </ul>
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="#" class="btn btn-primary">View Appointment Details</a>
                    <a href="#" class="btn">Contact Support</a>
                </div>
                
                <p>If you have any questions or need to make changes, please don\'t hesitate to contact us.</p>
                
                <p style="margin-top: 30px;">
                    <strong>Best regards,</strong><br>
                    The Service Platform Team
                </p>
            '
        ],
        'WELCOME_CAMPAIGN' => [
            'name' => 'Welcome Campaign',
            'subject' => '🎉 Welcome to Service Platform - Your Journey Starts Here!',
            'body' => '
                <div class="greeting">Hello New User,</div>
                
                <div class="highlight-box">
                    <h3>🎉 Welcome to the Service Platform Family!</h3>
                    <p>We\'re thrilled to have you join thousands of satisfied customers who trust us with their service needs.</p>
                </div>
                
                <div class="content-section">
                    <h2>🚀 Get Started in 3 Easy Steps</h2>
                    
                    <div class="info-box">
                        <h3>Step 1: Complete Your Profile</h3>
                        <p>Add your details to get personalized service recommendations.</p>
                        <a href="#" class="btn btn-primary">Complete Profile</a>
                    </div>
                    
                    <div class="info-box">
                        <h3>Step 2: Browse Our Services</h3>
                        <p>Discover hundreds of verified service providers in your area.</p>
                        <a href="#" class="btn btn-primary">Browse Services</a>
                    </div>
                    
                    <div class="info-box">
                        <h3>Step 3: Book Your First Appointment</h3>
                        <p>Choose your provider and schedule your appointment in minutes.</p>
                        <a href="#" class="btn btn-success">Book Now</a>
                    </div>
                </div>
                
                <div class="success-box">
                    <h3>🎁 Special Welcome Offer</h3>
                    <p>Get <strong>10% OFF</strong> your first service booking! Use code: <strong>WELCOME10</strong></p>
                    <p><em>Valid for the next 30 days</em></p>
                </div>
                
                <p>Need help getting started? Our friendly support team is ready to assist you every step of the way.</p>
                
                <p style="margin-top: 30px;">
                    <strong>Welcome aboard!</strong><br>
                    The Service Platform Team
                </p>
            '
        ]
    ];
    
    $selectedTemplate = $templates[$template] ?? $templates['NEW_APPOINTMENT'];
    
    // Simulate EmailTemplateService processing
    $gs = \App\Models\GeneralSetting::first();
    $siteName = $gs->site_name ?? 'Service Platform';
    $siteUrl = url('/');
    $logoUrl = asset('assets/images/logo_icon/logo.png');
    
    // Load professional wrapper
    $wrapperTemplate = view('email_templates.professional_wrapper')->render();
    
    // Replace placeholders
    $wrapperTemplate = str_replace('{{subject}}', $selectedTemplate['subject'], $wrapperTemplate);
    $wrapperTemplate = str_replace('{{logo_url}}', $logoUrl, $wrapperTemplate);
    $wrapperTemplate = str_replace('{{site_name}}', $siteName, $wrapperTemplate);
    $wrapperTemplate = str_replace('{{site_url}}', $siteUrl, $wrapperTemplate);
    $wrapperTemplate = str_replace('{{current_year}}', date('Y'), $wrapperTemplate);
    $wrapperTemplate = str_replace('{{user_email}}', 'user@example.com', $wrapperTemplate);
    $wrapperTemplate = str_replace('{{unsubscribe_url}}', $siteUrl . '/unsubscribe', $wrapperTemplate);
    $wrapperTemplate = str_replace('{!! $email_body !!}', $selectedTemplate['body'], $wrapperTemplate);
    
    return $wrapperTemplate;
});

// Test notification route
Route::get('/test-notifications', function() {
    return response()->json([
        'success' => true,
        'unread_count' => 3,
        'notifications' => [
            [
                'id' => 1,
                'title' => 'Test Notification 1',
                'message' => 'This is a test notification',
                'icon' => 'las la-bell',
                'color' => 'blue',
                'is_read' => false,
                'is_important' => false,
                'time_ago' => '5 minutes ago',
                'action_url' => null
            ],
            [
                'id' => 2,
                'title' => 'Test Notification 2', 
                'message' => 'Another test notification',
                'icon' => 'las la-info',
                'color' => 'green',
                'is_read' => false,
                'is_important' => true,
                'time_ago' => '10 minutes ago',
                'action_url' => null
            ]
        ]
    ]);
});



