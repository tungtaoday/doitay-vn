<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewLeadNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CustomerLeadController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Leads của tôi';
        
        $query = Lead::where('customer_id', Auth::id())
            ->with(['category', 'purchases.company'])
            ->withCount(['purchases', 'visibilities']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $leads = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('status', 1)->get();

        return view('Template::user.customer.leads.index', compact('pageTitle', 'leads', 'categories'));
    }

    public function create()
    {
        $pageTitle = 'Tạo Lead mới';
        $categories = Category::where('status', 1)->get();
        
        return view('Template::user.customer.leads.create', compact('pageTitle', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            // Validation rules - simplified for guest users
            $rules = [
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'district' => 'required|string',
                'ward' => 'required|string',
                'address' => 'required|string',
                'urgency' => 'required|in:low,medium,high',
                'budget_min' => 'nullable|numeric|min:0',
                'budget_max' => 'nullable|numeric|min:0',
                'needed_by' => 'nullable|date|after:today',
                'max_contractors' => 'nullable|integer|min:1|max:10',
                'min_rating' => 'nullable|numeric|min:0|max:5',
                'feature_requirements' => 'nullable|array',
                'selected_contractors' => 'nullable|array',
            ];

            $messages = [
                'category_id.required' => 'Vui lòng chọn loại công việc',
                'category_id.exists' => 'Loại công việc không hợp lệ',
                'title.required' => 'Vui lòng nhập tiêu đề công việc',
                'description.required' => 'Vui lòng mô tả chi tiết công việc',
                'district.required' => 'Vui lòng chọn quận/huyện',
                'ward.required' => 'Vui lòng chọn phường/xã',
                'address.required' => 'Vui lòng nhập địa chỉ cụ thể',
                'urgency.required' => 'Vui lòng chọn mức độ ưu tiên',
                'urgency.in' => 'Mức độ ưu tiên không hợp lệ',
                'needed_by.after' => 'Ngày cần hoàn thành phải sau ngày hôm nay',
                'budget_min.numeric' => 'Ngân sách tối thiểu phải là số',
                'budget_max.numeric' => 'Ngân sách tối đa phải là số',
            ];

            // For guest users, add contact information requirements
            if (!auth()->check()) {
                $rules['fullname'] = 'required|string|max:255';
                $rules['mobile'] = 'required|string|min:10|max:15';
                $rules['email'] = 'nullable|email|max:255';
                
                $messages['fullname.required'] = 'Vui lòng nhập họ tên';
                $messages['mobile.required'] = 'Vui lòng nhập số điện thoại';
                $messages['email.email'] = 'Email không hợp lệ';
            }

            $validated = $request->validate($rules, $messages);

            \Log::info('Lead creation request data:', $request->all());

            // Handle user creation for guests
            $customerId = null;
            $isNewUser = false;
            $generatedPassword = null;
            
            if (auth()->check()) {
                $customerId = auth()->id();
            } else {
                // Check if user already exists by mobile or email
                $user = null;
                
                // First try to find by mobile
                if (!empty($validated['mobile'])) {
                $user = \App\Models\User::where('mobile', $validated['mobile'])->first();
                }
                
                // If not found by mobile, try by email
                if (!$user && !empty($validated['email'])) {
                    $user = \App\Models\User::where('email', $validated['email'])->first();
                }
                
                if (!$user) {
                    // Generate random password for new user
                    $generatedPassword = \Str::random(8);
                    
                    // Parse fullname
                    $nameParts = explode(' ', trim($validated['fullname']));
                    $firstname = $nameParts[0] ?? '';
                    $lastname = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';
                    
                    // Create new user
                    $user = \App\Models\User::create([
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'username' => $validated['mobile'],
                        'email' => $validated['email'] ?: $validated['mobile'] . '@doitay.vn',
                        'mobile' => $validated['mobile'],
                        'password' => \Hash::make($generatedPassword),
                        'email_verified_at' => now(),
                        'mobile_verified_at' => now(),
                        'status' => 1, // Active user
                        'ev' => 1, // Email verified
                        'sv' => 1, // SMS verified
                        'profile_complete' => 0,
                    ]);
                    
                    $isNewUser = true;
                    
                    \Log::info('Created new user for guest lead:', [
                        'user_id' => $user->id,
                        'mobile' => $user->mobile,
                        'email' => $user->email,
                        'password' => $generatedPassword
                    ]);
                    
                    // Send welcome email with login credentials
                    $this->sendWelcomeEmail($user, $generatedPassword);
                    
                } else {
                    \Log::info('Found existing user for guest lead:', [
                        'user_id' => $user->id,
                        'mobile' => $user->mobile,
                        'email' => $user->email
                    ]);
                }
                
                // Auto login the user (new or existing)
                auth()->login($user);
                $customerId = $user->id;
            }

            // Create the lead
            $lead = \App\Models\Lead::create([
                'customer_id' => $customerId,
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['district'] . ', ' . $validated['ward'],
                'district' => $validated['district'],
                'ward' => $validated['ward'],
                'address' => ['detail' => $validated['address']],
                'budget_min' => $validated['budget_min'],
                'budget_max' => $validated['budget_max'],
                'urgency' => $validated['urgency'],
                'status' => 'active',
                'needed_by' => $validated['needed_by'],
                'max_contractors' => $validated['max_contractors'] ?? 5,
                'lead_price' => 10000, // Default lead price
                'expires_at' => now()->addDays(30),
                'requirements' => $validated['feature_requirements'] ?? [],
                'customer_info' => [
                    'min_rating' => $validated['min_rating'] ?? null,
                    'selected_contractors' => $validated['selected_contractors'] ?? [],
                ]
            ]);

            \Log::info('Lead created successfully:', ['lead_id' => $lead->id]);

            // Send confirmation email to customer
            $contractorsCount = 0;
            
            // Notify matching contractors
            if (!empty($validated['selected_contractors'])) {
                $contractorsCount = count($validated['selected_contractors']);
                $this->notifySelectedContractors($lead, $validated['selected_contractors']);
            } else {
                $contractorsCount = $this->notifyMatchingContractors($lead);
            }
            
            // Send confirmation email to customer
            if (auth()->check()) {
                try {
                    notify(auth()->user(), 'LEAD_CREATED_CONFIRMATION', [
                        'customer_name' => auth()->user()->firstname . ' ' . auth()->user()->lastname,
                        'lead_title' => $lead->title,
                        'lead_location' => $lead->location,
                        'lead_budget' => $lead->getBudgetRange(),
                        'lead_urgency' => ucfirst($lead->urgency),
                        'contractors_count' => $contractorsCount,
                        'lead_id' => $lead->id,
                        'lead_url' => route('user.customer.leads.show', $lead->id),
                        'current_time' => now()->format('d/m/Y H:i:s'),
                        'site_name' => gs('site_name'),
                        'support_phone' => '1900 1234'
                    ]);
                    
                    \Log::info('Customer confirmation email sent:', [
                        'lead_id' => $lead->id,
                        'customer_email' => auth()->user()->email
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send customer confirmation email:', [
                        'lead_id' => $lead->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => $isNewUser 
                    ? 'Lead đã được tạo thành công! Tài khoản mới đã được tạo và thông tin đăng nhập đã được gửi qua email/SMS.' 
                    : 'Lead đã được tạo thành công! Bạn sẽ nhận được liên hệ từ các thợ sớm.',
                'lead_id' => $lead->id,
                'user_created' => $isNewUser,
                'user_id' => $customerId,
                'login_info' => $isNewUser ? [
                    'username' => $validated['mobile'],
                    'password_sent' => true,
                    'email' => $validated['email'] ?: $validated['mobile'] . '@doitay.vn'
                ] : null,
                'redirect_url' => auth()->check() ? route('user.customer.leads.show', $lead->id) : null
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Lead validation failed:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Lead creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo lead. Vui lòng thử lại.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function notifySelectedContractors($lead, $selectedContractorIds)
    {
        try {
            $contractors = \App\Models\Company::whereIn('id', $selectedContractorIds)
                ->with('user')
                ->get();

            foreach ($contractors as $contractor) {
                if ($contractor->user) {
                    // Send Laravel notification
                    $contractor->user->notify(new \App\Notifications\NewLeadNotification($lead));
                    
                    // Send email via template system
                    notify($contractor->user, 'NEW_LEAD_NOTIFICATION', [
                        'contractor_name' => $contractor->user->firstname . ' ' . $contractor->user->lastname,
                        'lead_title' => $lead->title,
                        'lead_location' => $lead->location,
                        'lead_budget' => $lead->getBudgetRange(),
                        'lead_category' => $lead->category->name ?? 'Dịch vụ',
                        'lead_urgency' => ucfirst($lead->urgency),
                        'priority_score' => '5.0',
                        'lead_price' => number_format($lead->lead_price),
                        'lead_url' => route('user.leads.show', $lead->id),
                        'expires_at' => '24 giờ',
                        'current_time' => now()->format('d/m/Y H:i:s')
                    ]);
                    
                    // Add to our UserNotification system
                    \App\Models\UserNotification::createLeadNotification(
                        $contractor->user->id,
                        $lead,
                        'new_lead',
                        "Lead mới được chỉ định: {$lead->title}",
                        "Bạn được chỉ định để thực hiện công việc tại {$lead->location}. Ngân sách: {$lead->getBudgetRange()}",
                        route('user.leads.show', $lead->id)
                    );
                }
            }
            
            \Log::info('Notified selected contractors:', ['lead_id' => $lead->id, 'contractor_count' => $contractors->count()]);
        } catch (\Exception $e) {
            \Log::error('Failed to notify selected contractors:', ['error' => $e->getMessage()]);
        }
    }

    private function notifyMatchingContractors($lead)
    {
        try {
            // Find contractors in the same category and district with smart filtering
            $contractors = \App\Models\Company::where('category_id', $lead->category_id)
                ->where('district', $lead->district)
                ->where('status', 1) // APPROVED status
                ->with(['user', 'wallet']) // Remove 'ratings' since it doesn't exist
                ->get()
                ->map(function($company) {
                    // FIX: Use avg_rating from companies table instead of non-existent ratings table
                    $avgRating = (float)$company->avg_rating; // Use existing column
                    $reviewCount = 0; // Assume 0 since no ratings table exists
                    
                    // Weighted score: rating + review count bonus  
                    $company->smart_score = $avgRating + ($reviewCount * 0.1);
                    
                    return $company;
                })
                ->sortByDesc('smart_score') // Sort by highest score first
                ->take(3) // Only top 3 contractors
                ->filter(function($company) {
                    // Additional filters
                    return $company->user && 
                           $company->smart_score >= 3.0 && // Minimum rating 3.0
                           $company->hasActiveWallet(); // Must have wallet to participate
                });

            foreach ($contractors as $contractor) {
                // Create lead visibility record
                \App\Models\LeadVisibility::create([
                    'lead_id' => $lead->id,
                    'company_id' => $contractor->id,
                    'priority_score' => $contractor->smart_score,
                    'notified_at' => now(),
                    'expires_at' => now()->addHours(24) // 24h exclusive access
                ]);
                
                // Send Laravel notification
                $contractor->user->notify(new \App\Notifications\SmartLeadNotification($lead, $contractor->smart_score));
                
                // Add detailed logging for email notifications
                \Log::info('Attempting to send email notification:', [
                    'lead_id' => $lead->id,
                    'contractor_id' => $contractor->id,
                    'contractor_name' => $contractor->name,
                    'contractor_email' => $contractor->user->email,
                    'template' => 'NEW_LEAD_NOTIFICATION'
                ]);
                
                try {
                    // Send email via template system
                    notify($contractor->user, 'NEW_LEAD_NOTIFICATION', [
                        'contractor_name' => $contractor->user->firstname . ' ' . $contractor->user->lastname,
                        'lead_title' => $lead->title,
                        'lead_location' => $lead->location,
                        'lead_budget' => $lead->getBudgetRange(),
                        'lead_category' => $lead->category->name ?? 'Dịch vụ',
                        'lead_urgency' => ucfirst($lead->urgency),
                        'priority_score' => number_format($contractor->smart_score, 1),
                        'lead_price' => number_format($lead->lead_price),
                        'lead_url' => route('user.leads.show', $lead->id),
                        'expires_at' => '24 giờ',
                        'current_time' => now()->format('d/m/Y H:i:s')
                    ]);
                    
                    \Log::info('Email notification sent successfully:', [
                        'lead_id' => $lead->id,
                        'contractor_email' => $contractor->user->email
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Failed to send email notification:', [
                        'lead_id' => $lead->id,
                        'contractor_email' => $contractor->user->email,
                        'error' => $e->getMessage()
                    ]);
                }
                
                try {
                    // Add to our UserNotification system
                    \App\Models\UserNotification::createLeadNotification(
                        $contractor->user->id,
                        $lead,
                        'smart_lead',
                        "🎯 Lead ưu tiên: {$lead->title}",
                        "Bạn được chọn trong top 3 thợ cho công việc tại {$lead->location}. Ngân sách: {$lead->getBudgetRange()}. Thời gian độc quyền: 24h",
                        url("/user/leads/show/{$lead->id}")
                    );
                    
                    \Log::info('UserNotification created successfully:', [
                        'lead_id' => $lead->id,
                        'user_id' => $contractor->user->id
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Failed to create UserNotification:', [
                        'lead_id' => $lead->id,
                        'user_id' => $contractor->user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Also notify the customer about lead creation
            if (auth()->check()) {
                \App\Models\UserNotification::createLeadNotification(
                    auth()->id(),
                    $lead,
                    'lead_created',
                    "Lead đã được tạo: {$lead->title}",
                    "Lead của bạn đã được tạo thành công và đang được gửi đến {$contractors->count()} thợ phù hợp. Bạn sẽ sớm nhận được phản hồi.",
                    route('user.customer.leads.show', $lead->id)
                );
            }
            
            \Log::info('Smart lead distribution completed:', [
                'lead_id' => $lead->id, 
                'contractors_notified' => $contractors->count(),
                'contractors' => $contractors->pluck('name', 'id')->toArray()
            ]);
            
            return $contractors->count();
            
        } catch (\Exception $e) {
            \Log::error('Failed to distribute lead smartly:', ['error' => $e->getMessage()]);
            
            // Fallback to old method if smart distribution fails
            $this->fallbackNotifyContractors($lead);
            return 0;
        }
    }
    
    private function fallbackNotifyContractors($lead)
    {
        // Original logic as backup
        $contractors = \App\Models\Company::where('category_id', $lead->category_id)
            ->where('district', $lead->district)
            ->where('status', 1) // APPROVED status
            ->with('user')
            ->limit(5)
            ->get();

        foreach ($contractors as $contractor) {
            if ($contractor->user) {
                // Send Laravel notification
                $contractor->user->notify(new \App\Notifications\NewLeadNotification($lead));
                
                // Send email via template system
                notify($contractor->user, 'NEW_LEAD_NOTIFICATION', [
                    'contractor_name' => $contractor->user->firstname . ' ' . $contractor->user->lastname,
                    'lead_title' => $lead->title,
                    'lead_location' => $lead->location,
                    'lead_budget' => $lead->getBudgetRange(),
                    'lead_category' => $lead->category->name ?? 'Dịch vụ',
                    'lead_urgency' => ucfirst($lead->urgency),
                    'priority_score' => '4.0',
                    'lead_price' => number_format($lead->lead_price),
                    'lead_url' => route('user.leads.show', $lead->id),
                    'expires_at' => '24 giờ',
                    'current_time' => now()->format('d/m/Y H:i:s')
                ]);
                
                // Add to our UserNotification system
                \App\Models\UserNotification::createLeadNotification(
                    $contractor->user->id,
                    $lead,
                    'new_lead',
                    "Lead mới: {$lead->title}",
                    "Có lead mới phù hợp với dịch vụ của bạn tại {$lead->location}. Ngân sách: {$lead->getBudgetRange()}",
                    url("/user/leads/show/{$lead->id}")
                );
            }
        }
    }

    public function show($id)
    {
        $pageTitle = 'Chi tiết Lead';
        
        $lead = Lead::where('customer_id', Auth::id())
            ->with(['category', 'purchases.company'])
            ->findOrFail($id);

        return view('Template::user.customer.leads.show', compact('pageTitle', 'lead'));
    }

    public function edit($id)
    {
        $pageTitle = 'Chỉnh sửa Lead';
        
        $lead = Lead::where('customer_id', Auth::id())
            ->findOrFail($id);
            
        $categories = Category::where('status', 1)->get();

        return view('Template::user.customer.leads.edit', compact('pageTitle', 'lead', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::where('customer_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'address' => 'required|string',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'urgency' => 'required|in:low,medium,high',
            'needed_by' => 'nullable|date|after:today',
            'requirements' => 'nullable|array'
        ]);

        $lead->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->district . ', ' . $request->ward,
            'district' => $request->district,
            'ward' => $request->ward,
            'address' => ['detail' => $request->address],
            'budget_min' => $request->budget_min,
            'budget_max' => $request->budget_max,
            'urgency' => $request->urgency,
            'needed_by' => $request->needed_by,
            'requirements' => $request->requirements ?? []
        ]);

        $notify[] = ['success', 'Lead đã được cập nhật thành công!'];
        return redirect()->route('user.customer.leads.show', $lead->id)->withNotify($notify);
    }

    public function close($id)
    {
        $lead = Lead::where('customer_id', Auth::id())->findOrFail($id);
        
        $lead->update(['status' => 'closed']);

        // TODO: Notify all contractors who purchased this lead

        $notify[] = ['success', 'Lead đã được đóng thành công!'];
        return back()->withNotify($notify);
    }

    public function selectContractor(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $lead = Lead::where('customer_id', Auth::id())->findOrFail($id);
        
        // Check if company has purchased this lead
        $purchase = $lead->purchases()->where('company_id', $request->company_id)->first();
        
        if (!$purchase) {
            $notify[] = ['error', 'Công ty này chưa mua lead này!'];
            return back()->withNotify($notify);
        }

        // Close lead and mark selected contractor
        $lead->update(['status' => 'closed']);
        $purchase->update(['outcome' => 'won']);

        // Mark other contractors as lost
        $lead->purchases()->where('company_id', '!=', $request->company_id)
              ->update(['outcome' => 'lost']);

        $notify[] = ['success', 'Bạn đã chọn thợ thành công! Thợ sẽ sớm liên hệ với bạn.'];
        return back()->withNotify($notify);
    }

    private function sendWelcomeEmail($user, $password)
    {
        try {
            // Send email if email is valid
            if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL) && !str_contains($user->email, '@doitay.vn')) {
                \Mail::send('emails.welcome_guest', [
                    'user' => $user,
                    'password' => $password,
                    'login_url' => route('user.login.v2'),
                    'website_name' => config('app.name', 'DoiTay.vn')
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                           ->subject('Chào mừng bạn đến với ' . config('app.name', 'DoiTay.vn'));
                });
                
                \Log::info('Welcome email sent successfully:', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
            
            // Send SMS notification with password
            if (!empty($user->mobile)) {
                $smsMessage = "Chào mừng bạn đến với DoiTay.vn! Tài khoản: {$user->mobile}, Mật khẩu: {$password}. Đăng nhập tại: " . route('user.login.v2');
                
                // Use your SMS service here
                $this->sendSMS($user->mobile, $smsMessage);
                
                \Log::info('Welcome SMS sent successfully:', [
                    'user_id' => $user->id,
                    'mobile' => $user->mobile
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email/SMS:', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function sendSMS($mobile, $message)
    {
        try {
            // Implement SMS sending logic here
            // This is a placeholder - you should implement with your SMS provider
            
            // Example with a common SMS service:
            /*
            $smsService = new \YourSMSService();
            $smsService->send($mobile, $message);
            */
            
            \Log::info('SMS sending attempted:', [
                'mobile' => $mobile,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            \Log::error('SMS sending failed:', [
                'mobile' => $mobile,
                'error' => $e->getMessage()
            ]);
        }
    }
} 