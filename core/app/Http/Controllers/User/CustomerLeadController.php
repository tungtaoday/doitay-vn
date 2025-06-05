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
            if (auth()->check()) {
                $customerId = auth()->id();
            } else {
                // Create or find user for guest
                $user = \App\Models\User::where('mobile', $validated['mobile'])->first();
                
                if (!$user) {
                    $user = \App\Models\User::create([
                        'firstname' => explode(' ', $validated['fullname'])[0],
                        'lastname' => substr($validated['fullname'], strpos($validated['fullname'], ' ') + 1) ?: '',
                        'username' => $validated['mobile'],
                        'email' => $validated['email'] ?: $validated['mobile'] . '@doitay.vn',
                        'mobile' => $validated['mobile'],
                        'password' => \Hash::make('123456'), // Default password
                        'email_verified_at' => now(),
                        'mobile_verified_at' => now(),
                    ]);
                    
                    // Auto login the guest user
                    auth()->login($user);
                    \Log::info('Created new user for guest lead:', ['user_id' => $user->id]);
                }
                
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
                'message' => 'Lead đã được tạo thành công! Bạn sẽ nhận được liên hệ từ các thợ sớm.',
                'lead_id' => $lead->id,
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
} 