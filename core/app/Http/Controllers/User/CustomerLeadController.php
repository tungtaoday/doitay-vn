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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'address' => 'required|string',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'urgency' => 'required|in:low,medium,high',
            'needed_by' => 'nullable|date|after:today',
            'requirements' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:5120' // 5MB max
        ]);

        // Process attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lead-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize()
                ];
            }
        }

        // Create lead
        $lead = Lead::create([
            'customer_id' => Auth::id(),
            'category_id' => $request->category_id,
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
            'status' => 'active',
            'max_contractors' => 5, // Default
            'customer_info' => [
                'name' => Auth::user()->fullname,
                'phone' => Auth::user()->mobile,
                'email' => Auth::user()->email
            ],
            'requirements' => $request->requirements ?? [],
            'attachments' => $attachments,
            'expires_at' => now()->addDays(30),
            'lead_price' => 10000 // Default price
        ]);

        // TODO: Send notifications to relevant contractors
        $this->notifyRelevantContractors($lead);

        $notify[] = ['success', 'Lead đã được tạo thành công! Các thợ phù hợp sẽ sớm liên hệ với bạn.'];
        return redirect()->route('user.customer.leads.show', $lead->id)->withNotify($notify);
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

    private function notifyRelevantContractors(Lead $lead)
    {
        // Find companies in the same category and location
        $relevantCompanies = Company::where('category_id', $lead->category_id)
            ->where('status', \App\Constants\Status::APPROVED)
            ->whereHas('user', function($query) use ($lead) {
                $query->where('district', $lead->district)
                      ->orWhere('city', 'like', '%' . $lead->district . '%');
            })
            ->with('user')
            ->get();

        foreach ($relevantCompanies as $company) {
            if ($company->user) {
                Notification::send($company->user, new NewLeadNotification($lead));
            }
        }
    }
} 