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
    public function index()
    {
        $pageTitle = 'Leads của tôi';
        
        $leads = Lead::where('customer_id', Auth::id())
            ->with(['category', 'purchases.company'])
            ->latest()
            ->paginate(15);

        return view('Template::user.customer.leads.index', compact('pageTitle', 'leads'));
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
                'lead_price' => 50000, // Default lead price
                'expires_at' => now()->addDays(30),
                'requirements' => $validated['feature_requirements'] ?? [],
                'customer_info' => [
                    'min_rating' => $validated['min_rating'] ?? null,
                    'selected_contractors' => $validated['selected_contractors'] ?? [],
                ]
            ]);

            \Log::info('Lead created successfully:', ['lead_id' => $lead->id]);

            // Notify matching contractors
            if (!empty($validated['selected_contractors'])) {
                $this->notifySelectedContractors($lead, $validated['selected_contractors']);
            } else {
                $this->notifyMatchingContractors($lead);
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
                    $contractor->user->notify(new \App\Notifications\NewLeadNotification($lead));
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
            // Find contractors in the same category and district
            $contractors = \App\Models\Company::where('category_id', $lead->category_id)
                ->where('district', $lead->district)
                ->where('status', 1)
                ->where('is_approved', 1)
                ->with('user')
                ->limit(10) // Limit to prevent spam
                ->get();

            foreach ($contractors as $contractor) {
                if ($contractor->user) {
                    $contractor->user->notify(new \App\Notifications\NewLeadNotification($lead));
                }
            }
            
            \Log::info('Notified matching contractors:', ['lead_id' => $lead->id, 'contractor_count' => $contractors->count()]);
        } catch (\Exception $e) {
            \Log::error('Failed to notify matching contractors:', ['error' => $e->getMessage()]);
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