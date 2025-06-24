<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadPurchase;
use App\Models\LeadVisibility;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Quản lý Leads';
        
        $query = Lead::with(['customer', 'category', 'purchases.company', 'visibilities.company'])
            ->withCount(['purchases', 'visibilities']);
        
        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->district) {
            $query->where('district', $request->district);
        }
        
        if ($request->urgency) {
            $query->where('urgency', $request->urgency);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $leads = $query->latest()->paginate(20);
        
        // Stats
        $stats = [
            'total' => Lead::count(),
            'active' => Lead::where('status', 'active')->count(),
            'closed' => Lead::where('status', 'closed')->count(),
            'expired' => Lead::where('status', 'expired')->count(),
            'today' => Lead::whereDate('created_at', today())->count(),
            'this_week' => Lead::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_revenue' => LeadPurchase::sum('price_paid'),
            'avg_purchase_per_lead' => Lead::withCount('purchases')->get()->avg('purchases_count')
        ];
        
        $categories = Category::all();
        
        return view('admin.leads.index', compact('pageTitle', 'leads', 'stats', 'categories'));
    }
    
    public function show($id)
    {
        $pageTitle = 'Chi tiết Lead';
        
        $lead = Lead::with([
            'customer', 
            'category', 
            'purchases.company.user',
            'visibilities.company'
        ])->findOrFail($id);
        
        // Lead timeline
        $timeline = collect();
        
        // Lead created
        $timeline->push([
            'type' => 'created',
            'title' => 'Lead được tạo',
            'description' => "Lead được tạo bởi {$lead->customer->firstname} {$lead->customer->lastname}",
            'timestamp' => $lead->created_at,
            'icon' => 'fas fa-plus-circle',
            'color' => 'primary'
        ]);
        
        // Notifications sent
        foreach ($lead->visibilities as $visibility) {
            $timeline->push([
                'type' => 'notification',
                'title' => 'Thông báo gửi cho thợ',
                'description' => "Gửi thông báo cho {$visibility->company->name}",
                'timestamp' => $visibility->notified_at,
                'icon' => 'fas fa-bell',
                'color' => 'info',
                'data' => $visibility
            ]);
        }
        
        // Lead purchases
        foreach ($lead->purchases as $purchase) {
            $timeline->push([
                'type' => 'purchase',
                'title' => 'Thợ mua lead',
                'description' => "{$purchase->company->name} đã mua lead với giá " . number_format($purchase->price_paid) . "₫",
                'timestamp' => $purchase->created_at,
                'icon' => 'fas fa-shopping-cart',
                'color' => 'success',
                'data' => $purchase
            ]);
            
            // Purchase status updates
            if ($purchase->contacted_at) {
                $timeline->push([
                    'type' => 'contact',
                    'title' => 'Đã liên hệ khách hàng',
                    'description' => "{$purchase->company->name} đã liên hệ khách hàng",
                    'timestamp' => $purchase->contacted_at,
                    'icon' => 'fas fa-phone',
                    'color' => 'warning',
                    'data' => $purchase
                ]);
            }
            
            if ($purchase->quoted_at) {
                $timeline->push([
                    'type' => 'quote',
                    'title' => 'Đã báo giá',
                    'description' => "{$purchase->company->name} đã báo giá " . number_format($purchase->quote_amount) . "₫",
                    'timestamp' => $purchase->quoted_at,
                    'icon' => 'fas fa-dollar-sign',
                    'color' => 'info',
                    'data' => $purchase
                ]);
            }
            
            if ($purchase->outcome === 'won') {
                $timeline->push([
                    'type' => 'won',
                    'title' => 'Thắng thầu',
                    'description' => "{$purchase->company->name} đã được khách hàng chọn",
                    'timestamp' => $purchase->updated_at,
                    'icon' => 'fas fa-trophy',
                    'color' => 'success',
                    'data' => $purchase
                ]);
            }
        }
        
        // Lead closed/expired
        if ($lead->status === 'closed' || $lead->status === 'expired') {
            $timeline->push([
                'type' => 'closed',
                'title' => $lead->status === 'closed' ? 'Lead đã đóng' : 'Lead hết hạn',
                'description' => $lead->status === 'closed' ? 'Lead đã được đóng' : 'Lead đã hết thời gian hiệu lực',
                'timestamp' => $lead->updated_at,
                'icon' => 'fas fa-times-circle',
                'color' => 'danger'
            ]);
        }
        
        // Sort timeline by timestamp
        $timeline = $timeline->sortBy('timestamp');
        
        // Related notifications
        $notifications = UserNotification::where('type', 'lead')
            ->whereJsonContains('data->lead_id', $lead->id)
            ->with('user')
            ->latest()
            ->get();
        
        return view('admin.leads.show', compact('pageTitle', 'lead', 'timeline', 'notifications'));
    }
    
    public function analytics()
    {
        $pageTitle = 'Phân tích Leads';
        
        // Lead creation trends (last 30 days)
        $leadTrends = Lead::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Conversion rates by category
        $categoryStats = Category::withCount(['leads', 'leads as purchased_leads' => function($query) {
            $query->whereHas('purchases');
        }])->get()->map(function($category) {
            $purchaseRate = $category->leads_count > 0 
                ? round(($category->purchased_leads / $category->leads_count) * 100, 2)
                : 0;
            
            return [
                'name' => $category->name,
                'total_leads' => $category->leads_count,
                'purchased_leads' => $category->purchased_leads,
                'purchase_rate' => $purchaseRate
            ];
        });
        
        // Top performing districts
        $districtStats = Lead::selectRaw('district, COUNT(*) as total_leads')
            ->selectRaw('COUNT(CASE WHEN purchased_count > 0 THEN 1 END) as leads_with_purchases')
            ->groupBy('district')
            ->havingRaw('COUNT(*) >= 5')
            ->orderByDesc('total_leads')
            ->limit(10)
            ->get();
        
        // Lead status distribution
        $statusDistribution = Lead::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Average response time (notification to first purchase)
        $avgResponseTime = DB::table('lead_visibilities')
            ->join('lead_purchases', 'lead_visibilities.lead_id', '=', 'lead_purchases.lead_id')
            ->whereNotNull('lead_visibilities.notified_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, lead_visibilities.notified_at, lead_purchases.created_at)) as avg_minutes')
            ->value('avg_minutes');
        
        // Revenue analytics
        $revenueStats = [
            'total_revenue' => LeadPurchase::sum('price_paid'),
            'avg_lead_price' => Lead::avg('lead_price'),
            'total_purchases' => LeadPurchase::count(),
            'unique_buyers' => LeadPurchase::distinct('company_id')->count()
        ];
        
        return view('admin.leads.analytics', compact(
            'pageTitle', 
            'leadTrends', 
            'categoryStats', 
            'districtStats', 
            'statusDistribution',
            'avgResponseTime',
            'revenueStats'
        ));
    }
    
    public function notifications()
    {
        $pageTitle = 'Lead Notifications';
        
        $notifications = UserNotification::where('type', 'lead')
            ->with(['user'])
            ->latest()
            ->paginate(50);
        
        // Notification stats
        $stats = [
            'total_sent' => UserNotification::where('type', 'lead')->count(),
            'read_rate' => UserNotification::where('type', 'lead')->where('is_read', true)->count() / max(UserNotification::where('type', 'lead')->count(), 1) * 100,
            'clicked_notifications' => UserNotification::where('type', 'lead')->whereNotNull('read_at')->count(),
            'smart_leads' => UserNotification::where('type', 'lead')->whereJsonContains('data->lead_type', 'smart_lead')->count()
        ];
        
        return view('admin.leads.notifications', compact('pageTitle', 'notifications', 'stats'));
    }
    
    public function companies()
    {
        $pageTitle = 'Hiệu suất Công ty';
        
        $companies = Company::select('companies.*')
            ->selectRaw('COUNT(lead_purchases.id) as leads_purchased')
            ->selectRaw('COUNT(DISTINCT user_notifications.id) as notifications_received')
            ->withSum('leadPurchases', 'price_paid')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['user'])
            ->withCount([
                'leadPurchases as won_leads' => function($query) {
                    $query->where('outcome', 'won');
                },
                'leadPurchases as contacted_leads' => function($query) {
                    $query->whereNotNull('contacted_at');
                }
            ])
            ->having('lead_purchases_count', '>', 0)
            ->orderByDesc('lead_purchases_count')
            ->paginate(20);
        
        $companies->getCollection()->transform(function($company) {
            $company->win_rate = $company->lead_purchases_count > 0 
                ? round(($company->won_leads / $company->lead_purchases_count) * 100, 2)
                : 0;
            
            $company->contact_rate = $company->lead_purchases_count > 0
                ? round(($company->contacted_leads / $company->lead_purchases_count) * 100, 2)
                : 0;
                
            return $company;
        });
        
        return view('admin.leads.companies', compact('pageTitle', 'companies'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,closed,expired'
        ]);
        
        $lead = Lead::findOrFail($id);
        $oldStatus = $lead->status;
        
        $lead->update(['status' => $request->status]);
        
        // Log status change
        \Log::info('Admin changed lead status', [
            'lead_id' => $lead->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'admin_id' => auth('admin')->id()
        ]);
        
        $notify[] = ['success', 'Trạng thái lead đã được cập nhật thành công!'];
        return back()->withNotify($notify);
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'leads' => 'required|array',
            'action' => 'required|in:close,expire,delete'
        ]);
        
        $leads = Lead::whereIn('id', $request->leads)->get();
        
        foreach ($leads as $lead) {
            switch ($request->action) {
                case 'close':
                    $lead->update(['status' => 'closed']);
                    break;
                case 'expire':
                    $lead->update(['status' => 'expired']);
                    break;
                case 'delete':
                    $lead->delete();
                    break;
            }
        }
        
        $notify[] = ['success', "Đã thực hiện {$request->action} cho " . count($leads) . " leads"];
        return back()->withNotify($notify);
    }
} 