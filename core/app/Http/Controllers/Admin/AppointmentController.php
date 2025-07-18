<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Company;
use App\Models\CompanyWallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Quản lý Appointments';
        
        $query = Appointment::with(['user', 'company.user', 'company.wallet'])
            ->latest();
        
        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $appointments = $query->paginate(20);
        
        // Overall Statistics
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'today' => Appointment::whereDate('created_at', today())->count(),
            'this_week' => Appointment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Appointment::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'total_revenue' => Appointment::where('status', 'confirmed')->count() * 50000, // 50k per confirmation
            'avg_confirmation_time' => $this->getAverageConfirmationTime()
        ];
        
        $companies = Company::where('status', 1)->get();
        
        return view('admin.appointments.index', compact('pageTitle', 'appointments', 'stats', 'companies'));
    }
    
    public function analytics()
    {
        $pageTitle = 'Appointment Analytics';
        
        // Daily appointment trends (last 30 days)
        $appointmentTrends = Appointment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Status distribution
        $statusDistribution = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Confirmation rates by company
        $companyStats = Company::select('companies.*')
            ->withCount([
                'appointments as total_appointments',
                'appointments as confirmed_appointments' => function($query) {
                    $query->where('status', 'confirmed');
                },
                'appointments as completed_appointments' => function($query) {
                    $query->where('status', 'completed');
                },
                'appointments as cancelled_appointments' => function($query) {
                    $query->where('status', 'cancelled');
                }
            ])
            ->with('wallet')
            ->having('total_appointments', '>', 0)
            ->orderByDesc('total_appointments')
            ->limit(10)
            ->get();
        
        // Calculate rates for each company
        $companyStats->transform(function($company) {
            $company->confirmation_rate = $company->total_appointments > 0 
                ? round(($company->confirmed_appointments / $company->total_appointments) * 100, 2)
                : 0;
            
            $company->completion_rate = $company->confirmed_appointments > 0
                ? round(($company->completed_appointments / $company->confirmed_appointments) * 100, 2)
                : 0;
                
            $company->earnings = $company->confirmed_appointments * 50000; // 50k per confirmation
            
            return $company;
        });
        
        // Revenue analytics
        $revenueStats = [
            'total_revenue' => Appointment::where('status', 'confirmed')->count() * 50000,
            'revenue_this_month' => Appointment::where('status', 'confirmed')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count() * 50000,
            'avg_revenue_per_company' => $companyStats->isNotEmpty() ? $companyStats->avg('earnings') : 0,
            'total_wallet_balance' => CompanyWallet::sum('balance') ?? 0
        ];
        
        // Time-based analytics
        $timeStats = [
            'avg_confirmation_time' => $this->getAverageConfirmationTime() ?? 0,
            'avg_completion_time' => $this->getAverageCompletionTime() ?? 0,
            'peak_hours' => $this->getPeakAppointmentHours(),
            'peak_days' => $this->getPeakAppointmentDays()
        ];
        
        return view('admin.appointments.analytics', compact(
            'pageTitle', 
            'appointmentTrends', 
            'statusDistribution', 
            'companyStats',
            'revenueStats',
            'timeStats'
        ));
    }
    
    public function companyPerformance()
    {
        $pageTitle = 'Hiệu suất Thợ';
        
        $companies = Company::select('companies.*')
            ->withCount([
                'appointments as total_appointments',
                'appointments as confirmed_appointments' => function($query) {
                    $query->where('status', 'confirmed');
                },
                'appointments as completed_appointments' => function($query) {
                    $query->where('status', 'completed');
                },
                'appointments as cancelled_appointments' => function($query) {
                    $query->where('status', 'cancelled');
                }
            ])
            ->with(['wallet', 'user'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('total_appointments', '>', 0)
            ->orderByDesc('total_appointments')
            ->paginate(20);
        
        // Enhanced metrics calculation
        $companies->getCollection()->transform(function($company) {
            // Performance rates
            $company->confirmation_rate = $company->total_appointments > 0 
                ? round(($company->confirmed_appointments / $company->total_appointments) * 100, 2)
                : 0;
            
            $company->completion_rate = $company->confirmed_appointments > 0
                ? round(($company->completed_appointments / $company->confirmed_appointments) * 100, 2)
                : 0;
            
            $company->cancellation_rate = $company->total_appointments > 0
                ? round(($company->cancelled_appointments / $company->total_appointments) * 100, 2)
                : 0;
            
            // Financial metrics
            $company->earnings = $company->confirmed_appointments * 50000;
            $company->wallet_balance = $company->wallet ? $company->wallet->balance : 0;
            $company->roi = $company->wallet_balance > 0 
                ? round(($company->earnings / $company->wallet_balance) * 100, 2)
                : 0;
            
            // Time-based metrics
            $company->avg_confirmation_time = $this->getCompanyAverageConfirmationTime($company->id);
            
            // Overall performance score (weighted average)
            $company->performance_score = round(
                ($company->confirmation_rate * 0.4) + 
                ($company->completion_rate * 0.3) + 
                ((100 - $company->cancellation_rate) * 0.2) + 
                (($company->reviews_avg_rating ?? 0) * 20 * 0.1), 2
            );
            
            return $company;
        });
        
        return view('admin.appointments.company_performance', compact('pageTitle', 'companies'));
    }
    
    public function walletAnalytics()
    {
        $pageTitle = 'Phân tích Ví Thợ';
        
        // Wallet statistics
        $walletStats = [
            'total_wallets' => CompanyWallet::count(),
            'active_wallets' => CompanyWallet::where('is_active', true)->count(),
            'total_balance' => CompanyWallet::sum('balance'),
            'avg_balance' => CompanyWallet::avg('balance'),
            'low_balance_wallets' => CompanyWallet::where('balance', '<', 100000)->count(), // < 100k
            'empty_wallets' => CompanyWallet::where('balance', 0)->count()
        ];
        
        // Top wallets by balance
        $topWallets = CompanyWallet::with('company')
            ->orderByDesc('balance')
            ->limit(10)
            ->get();
        
        // Companies without wallets
        $companiesWithoutWallet = Company::whereDoesntHave('wallet')
            ->where('status', 1)
            ->count();
        
        // Spending patterns (appointments that consumed wallet)
        $spendingStats = Appointment::where('status', 'confirmed')
            ->with('company.wallet')
            ->selectRaw('company_id, COUNT(*) as confirmed_count')
            ->groupBy('company_id')
            ->orderByDesc('confirmed_count')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->total_spent = $item->confirmed_count * 50000;
                $item->current_balance = ($item->company && $item->company->wallet) ? $item->company->wallet->balance : 0;
                return $item;
            });
        
        return view('admin.appointments.wallet_analytics', compact(
            'pageTitle', 
            'walletStats', 
            'topWallets', 
            'companiesWithoutWallet',
            'spendingStats'
        ));
    }
    
    public function show($id)
    {
        $pageTitle = 'Chi tiết Appointment';
        
        $appointment = Appointment::with([
            'user', 
            'company.user', 
            'company.wallet'
        ])->findOrFail($id);
        
        // Appointment timeline
        $timeline = collect();
        
        // Created
        $timeline->push([
            'type' => 'created',
            'title' => 'Appointment được tạo',
            'description' => "Khách hàng {$appointment->recipient_name} đặt lịch",
            'timestamp' => $appointment->created_at,
            'icon' => 'fas fa-calendar-plus',
            'color' => 'primary'
        ]);
        
        // Status changes
        if ($appointment->status === 'confirmed') {
            $timeline->push([
                'type' => 'confirmed',
                'title' => 'Thợ xác nhận',
                'description' => "{$appointment->company->name} đã xác nhận và thanh toán 50k",
                'timestamp' => $appointment->updated_at,
                'icon' => 'fas fa-check-circle',
                'color' => 'success'
            ]);
        }
        
        if ($appointment->status === 'completed') {
            $timeline->push([
                'type' => 'completed',
                'title' => 'Hoàn thành',
                'description' => "Công việc đã được hoàn thành",
                'timestamp' => $appointment->updated_at,
                'icon' => 'fas fa-flag-checkered',
                'color' => 'success'
            ]);
        }
        
        if ($appointment->status === 'cancelled') {
            $timeline->push([
                'type' => 'cancelled',
                'title' => 'Đã hủy',
                'description' => "Appointment đã bị hủy",
                'timestamp' => $appointment->updated_at,
                'icon' => 'fas fa-times-circle',
                'color' => 'danger'
            ]);
        }
        
        // Sort timeline
        $timeline = $timeline->sortBy('timestamp');
        
        return view('admin.appointments.show', compact('pageTitle', 'appointment', 'timeline'));
    }
    
    // Helper Methods
    private function getAverageConfirmationTime()
    {
        return DB::table('appointments')
            ->where('status', 'confirmed')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes')
            ->value('avg_minutes') ?? 0;
    }
    
    private function getAverageCompletionTime()
    {
        return DB::table('appointments')
            ->where('status', 'completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->value('avg_hours') ?? 0;
    }
    
    private function getCompanyAverageConfirmationTime($companyId)
    {
        return DB::table('appointments')
            ->where('company_id', $companyId)
            ->where('status', 'confirmed')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes')
            ->value('avg_minutes') ?? 0;
    }
    
    private function getPeakAppointmentHours()
    {
        return Appointment::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->limit(3)
            ->get();
    }
    
    private function getPeakAppointmentDays()
    {
        return Appointment::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByDesc('count')
            ->get();
    }
} 