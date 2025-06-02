<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Appointment;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Lấy thông tin ví
        $wallet = $user->wallet;
        
        // Tính tổng lượt xem của tất cả công ty
        $totalViews = $user->companies()->sum('views');
        
        // Tính tổng lượt đặt lịch
        $totalAppointments = Appointment::whereIn('company_id', $user->companies()->pluck('id'))->count();
        
        // Tính tổng lượt feedback
        $totalFeedbacks = Feedback::whereIn('company_id', $user->companies()->pluck('id'))->count();
        
        // Lấy thông tin chi tiết của từng công ty
        $companies = $user->companies()->withCount(['appointments', 'feedbacks'])
            ->get()
            ->map(function ($company) {
                return [
                    'name' => $company->name,
                    'views' => $company->views,
                    'appointments' => $company->appointments_count,
                    'feedbacks' => $company->feedbacks_count,
                    'revenue' => $company->appointments()->sum('amount')
                ];
            });
        
        // Gán các thuộc tính cho user để sử dụng trong view
        $user->wallet_balance = $wallet->balance ?? 0;
        $user->wallet_currency = $wallet->currency ?? 'VND';
        $user->total_views = $totalViews;
        $user->total_appointments = $totalAppointments;
        $user->total_feedbacks = $totalFeedbacks;
        $user->companies = $companies;
        
        return view('user.dashboard', compact('user'));
    }
} 