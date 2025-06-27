<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadPurchase;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Leads Dành Cho Bạn';
        
        $userCompanyIds = Auth::user()->companies->pluck('id');
        
        // Get leads that are visible to user's companies
        $visibleLeadIds = \App\Models\LeadVisibility::whereIn('company_id', $userCompanyIds)
            ->active()
            ->pluck('lead_id');
        
        $query = Lead::with(['category', 'customer'])
            ->whereIn('id', $visibleLeadIds)
            ->available()
            ->latest();

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by location
        if ($request->district) {
            $query->where('district', $request->district);
        }

        if ($request->ward) {
            $query->where('ward', $request->ward);
        }

        // Filter by budget
        if ($request->budget_min) {
            $query->where('budget_max', '>=', $request->budget_min);
        }

        if ($request->budget_max) {
            $query->where('budget_min', '<=', $request->budget_max);
        }

        // Filter by urgency
        if ($request->urgency) {
            $query->where('urgency', $request->urgency);
        }

        $leads = $query->paginate(15);
        
        // Add visibility info to each lead
        $leads->getCollection()->transform(function ($lead) use ($userCompanyIds) {
            $visibility = \App\Models\LeadVisibility::where('lead_id', $lead->id)
                ->whereIn('company_id', $userCompanyIds)
                ->first();
            
            $lead->visibility = $visibility;
            return $lead;
        });
        
        $categories = Category::all();

        // Stats for smart leads
        $stats = [
            'exclusive_leads' => \App\Models\LeadVisibility::whereIn('company_id', $userCompanyIds)
                ->active()
                ->count(),
            'high_priority' => \App\Models\LeadVisibility::whereIn('company_id', $userCompanyIds)
                ->active()
                ->where('priority_score', '>=', 4.0)
                ->count(),
            'expiring_soon' => \App\Models\LeadVisibility::whereIn('company_id', $userCompanyIds)
                ->active()
                ->where('expires_at', '<=', now()->addHours(6))
                ->count()
        ];

        return view('Template::user.leads.index', compact('pageTitle', 'leads', 'categories', 'stats'));
    }

    public function show($id)
    {
        $pageTitle = 'Chi tiết Lead';
        $lead = Lead::with(['category', 'customer', 'purchases.company'])
            ->findOrFail($id);

        // Check if current user's company already purchased this lead
        $userCompanies = Auth::user()->companies;
        $hasPurchased = false;
        $userPurchase = null;

        foreach ($userCompanies as $company) {
            $purchase = $lead->purchases()->where('company_id', $company->id)->first();
            if ($purchase) {
                $hasPurchased = true;
                $userPurchase = $purchase;
                break;
            }
        }

        return view('Template::user.leads.show', compact('pageTitle', 'lead', 'hasPurchased', 'userPurchase'));
    }

    public function purchase(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $lead = Lead::findOrFail($id);
        $company = Company::where('id', $request->company_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            // Check if lead can be purchased
            if (!$lead->canBePurchasedBy($company->id)) {
                throw new \Exception('Lead này không thể mua được hoặc bạn đã mua rồi');
            }

            // Get or create wallet
            $wallet = CompanyWallet::createForCompany($company);

            // Purchase the lead
            $purchase = $wallet->purchaseLead($lead, $company);

            $notify[] = ['success', 'Mua lead thành công! Thông tin khách hàng đã được mở khóa.'];
            return redirect()->route('user.leads.my-purchases')->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function myPurchases()
    {
        $pageTitle = 'Leads đã mua';
        
        $userCompanyIds = Auth::user()->companies->pluck('id');
        
        $purchases = LeadPurchase::with(['lead.category', 'company'])
            ->whereIn('company_id', $userCompanyIds)
            ->latest()
            ->paginate(15);

        return view('Template::user.leads.my-purchases', compact('pageTitle', 'purchases'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:contacted,quoted,won,lost',
            'notes' => 'nullable|string',
            'quote_amount' => 'nullable|numeric|min:0'
        ]);

        $purchase = LeadPurchase::where('user_id', Auth::id())->findOrFail($id);

        switch ($request->status) {
            case 'contacted':
                $purchase->markAsContacted($request->notes);
                break;
            case 'quoted':
                if (!$request->quote_amount) {
                    $notify[] = ['error', 'Vui lòng nhập số tiền báo giá'];
                    return back()->withNotify($notify);
                }
                $purchase->markAsQuoted($request->quote_amount, $request->notes);
                break;
            case 'won':
                $purchase->markAsWon($request->notes);
                break;
            case 'lost':
                $purchase->markAsLost($request->notes);
                break;
        }

        $notify[] = ['success', 'Cập nhật trạng thái thành công'];
        return back()->withNotify($notify);
    }

    public function dashboard()
    {
        $pageTitle = 'Dashboard Leads';
        
        $userCompanyIds = Auth::user()->companies->pluck('id');
        
        // Statistics
        $stats = [
            'total_purchased' => LeadPurchase::whereIn('company_id', $userCompanyIds)->count(),
            'contacted' => LeadPurchase::whereIn('company_id', $userCompanyIds)->contacted()->count(),
            'quoted' => LeadPurchase::whereIn('company_id', $userCompanyIds)->quoted()->count(),
            'won' => LeadPurchase::whereIn('company_id', $userCompanyIds)->won()->count(),
            'available_leads' => Lead::available()->count()
        ];

        // Recent purchases
        $recentPurchases = LeadPurchase::with(['lead.category', 'company'])
            ->whereIn('company_id', $userCompanyIds)
            ->latest()
            ->take(5)
            ->get();

        // Wallet info
        $wallets = CompanyWallet::whereIn('company_id', $userCompanyIds)
            ->with('company')
            ->get();

        return view('Template::user.leads.dashboard', compact('pageTitle', 'stats', 'recentPurchases', 'wallets'));
    }

    // NEW METHODS FOR CONTRACTOR SELF-REPORT FLOW
    
    /**
     * Contractor reports being selected by customer
     */
    public function reportSelected(Request $request, $purchaseId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $userCompanyIds = Auth::user()->companies->pluck('id');
        
        $purchase = LeadPurchase::with(['lead', 'company'])
            ->whereIn('company_id', $userCompanyIds)
            ->findOrFail($purchaseId);

        // Check if already reported
        if ($purchase->contractor_reported) {
            $notify[] = ['warning', 'Bạn đã báo cáo được chọn rồi!'];
            return back()->withNotify($notify);
        }

        // Check if lead is still active
        if ($purchase->lead->status !== 'active') {
            $notify[] = ['error', 'Lead này đã đóng hoặc hết hạn!'];
            return back()->withNotify($notify);
        }

        try {
            // Mark as reported
            $purchase->reportSelected($request->notes);

            // Send notification to customer
            $customer = $purchase->lead->customer;
            if ($customer) {
                // Create notification
                \App\Models\UserNotification::createLeadNotification(
                    $customer->id,
                    $purchase->lead,
                    'contractor_reported_selected',
                    "🎯 Thợ {$purchase->company->name} báo bạn đã chọn họ",
                    "Thợ {$purchase->company->name} báo cáo rằng bạn đã chọn họ cho công việc '{$purchase->lead->title}'. Vui lòng xác nhận thông tin này.",
                    route('user.customer.leads.show', $purchase->lead->id)
                );

                // Send email notification
                notify($customer, 'CONTRACTOR_REPORTS_SELECTED', [
                    'customer_name' => $customer->fullname ?? $customer->username,
                    'contractor_name' => $purchase->company->name,
                    'lead_title' => $purchase->lead->title,
                    'lead_location' => $purchase->lead->location,
                    'lead_budget' => $purchase->lead->getBudgetRange(),
                    'report_notes' => $request->notes ?? 'Không có ghi chú',
                    'confirm_url' => route('user.customer.leads.show', $purchase->lead->id)
                ]);
            }

            $notify[] = ['success', 'Đã gửi yêu cầu xác nhận cho khách hàng! Khách hàng sẽ xác nhận trong thời gian sớm nhất.'];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', 'Có lỗi xảy ra: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /**
     * Customer confirms contractor selection
     */
    public function customerConfirm(Request $request, $purchaseId)
    {
        $request->validate([
            'confirm' => 'required|boolean',
            'notes' => 'nullable|string|max:500'
        ]);

        $purchase = LeadPurchase::with(['lead', 'company'])
            ->whereHas('lead', function($query) {
                $query->where('customer_id', Auth::id());
            })
            ->findOrFail($purchaseId);

        // Check if contractor has reported
        if (!$purchase->contractor_reported) {
            $notify[] = ['error', 'Thợ này chưa báo cáo được chọn!'];
            return back()->withNotify($notify);
        }

        // Check if already confirmed
        if ($purchase->customer_confirmed) {
            $notify[] = ['warning', 'Bạn đã xác nhận rồi!'];
            return back()->withNotify($notify);
        }

        try {
            if ($request->confirm) {
                // Customer confirms
                $purchase->confirmSelection($request->notes);
                
                // Complete the lead
                $purchase->lead->completeWithContractor($purchase->company_id);

                // Send success notifications
                $contractor = $purchase->company->user;
                if ($contractor) {
                    // Notification to contractor
                    \App\Models\UserNotification::createLeadNotification(
                        $contractor->id,
                        $purchase->lead,
                        'customer_confirmed_selection',
                        "✅ Khách hàng đã xác nhận chọn bạn!",
                        "Chúc mừng! Khách hàng đã xác nhận chọn bạn cho công việc '{$purchase->lead->title}'. Lead đã được hoàn thành.",
                        route('user.leads.my-purchases')
                    );

                    // Email to contractor
                    notify($contractor, 'CUSTOMER_CONFIRMED_SELECTION', [
                        'contractor_name' => $purchase->company->name,
                        'customer_name' => Auth::user()->fullname ?? Auth::user()->username,
                        'lead_title' => $purchase->lead->title,
                        'lead_location' => $purchase->lead->location,
                        'lead_budget' => $purchase->lead->getBudgetRange(),
                        'confirmation_notes' => $request->notes ?? 'Không có ghi chú',
                        'customer_phone' => Auth::user()->mobile ?? 'Chưa cập nhật',
                        'customer_email' => Auth::user()->email
                    ]);
                }

                $notify[] = ['success', 'Đã xác nhận chọn thợ thành công! Lead đã được hoàn thành.'];
            } else {
                // Customer rejects
                $purchase->rejectClaim($request->notes);

                // Notify contractor about rejection
                $contractor = $purchase->company->user;
                if ($contractor) {
                    \App\Models\UserNotification::createLeadNotification(
                        $contractor->id,
                        $purchase->lead,
                        'customer_rejected_claim',
                        "❌ Khách hàng chưa chọn bạn",
                        "Khách hàng cho biết họ chưa chọn bạn cho công việc '{$purchase->lead->title}'. Bạn có thể tiếp tục liên hệ để thuyết phục khách hàng.",
                        route('user.leads.my-purchases')
                    );
                }

                $notify[] = ['info', 'Đã từ chối xác nhận. Thợ có thể tiếp tục liên hệ với bạn.'];
            }

            return back()->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', 'Có lỗi xảy ra: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
