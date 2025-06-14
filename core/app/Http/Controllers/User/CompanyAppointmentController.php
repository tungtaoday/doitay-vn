<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Company;
use App\Services\CompanyStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Notifications\AppointmentConfirmedNotification;
use App\Notifications\AppointmentCanceledNotification;
use App\Notifications\AppointmentCompletedNotification;

class CompanyAppointmentController extends Controller
{
    protected $statisticsService;

    public function __construct(CompanyStatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index()
    {
        $pageTitle = 'Quản lý lịch hẹn';
        $user = Auth::user();
        $appointments = Appointment::whereIn('company_id', $user->companies->pluck('id'))
            ->with('company')
            ->latest()
            ->get();

        // Get wallet information for all companies
        $companies = $user->companies()->with('wallet')->get();
        $totalWalletBalance = 0;
        $companiesWithoutWallet = [];
        
        foreach ($companies as $company) {
            if ($company->wallet) {
                $totalWalletBalance += $company->wallet->balance;
            } else {
                $companiesWithoutWallet[] = $company;
            }
        }

        // Calculate potential earnings from pending appointments
        $pendingAppointments = $appointments->where('status', 'pending');
        $potentialCost = $pendingAppointments->count() * 50000; // 50k per appointment
        
        // Check if user can afford all pending appointments
        $canAffordAll = $totalWalletBalance >= $potentialCost;
        
        // Calculate statistics
        $stats = [
            'total_balance' => $totalWalletBalance,
            'pending_cost' => $potentialCost,
            'can_afford_all' => $canAffordAll,
            'companies_without_wallet' => $companiesWithoutWallet,
            'appointments_this_month' => $appointments->where('created_at', '>=', now()->startOfMonth())->count(),
            'revenue_this_month' => $appointments->where('status', 'confirmed')->where('created_at', '>=', now()->startOfMonth())->count() * 50000,
        ];

        return view(activeTemplate() . 'user.companyAppointment', compact('pageTitle', 'appointments', 'user', 'stats', 'companies'));
    }

    public function confirm($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $user = Auth::user();

        if (!$user->companies->pluck('id')->contains($appointment->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot confirm a non-pending appointment.');
        }

        $company = Company::find($appointment->company_id);
        
        // Check if company has wallet and sufficient funds
        $wallet = $company->wallet;
        if (!$wallet) {
            return redirect()->back()->with('error', 'Vui lòng tạo ví cho công ty trước khi xác nhận lịch hẹn.');
        }

        $leadAccessFee = 50000; // 50k VND to access customer info
        
        if (!$wallet->hasSufficientFunds($leadAccessFee)) {
            return redirect()->back()->with('error', 'Số dư ví không đủ để truy cập thông tin khách hàng. Vui lòng nạp thêm tiền.');
        }

        // Charge the fee
        try {
            $wallet->deductFunds(
                $leadAccessFee,
                'customer_info_access',
                'Phí truy cập thông tin khách hàng - ' . $appointment->recipient_name,
                ['appointment_id' => $appointment->id]
            );

            $appointment->status = 'confirmed';
            $appointment->customer_info_unlocked = true;
            $appointment->save();

            // Increment hires count
            $this->statisticsService->incrementHires($company);

            // Send notifications
            $customer = User::find($appointment->user_id);
            if ($customer) {
                NotificationFacade::send($customer, new AppointmentConfirmedNotification($appointment));
            }
            NotificationFacade::send($user, new AppointmentConfirmedNotification($appointment));

            return redirect()->back()->with('success', 'Xác nhận lịch hẹn thành công! Thông tin khách hàng đã được mở khóa.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function cancel($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $user = Auth::user();

        if (!$user->companies->pluck('id')->contains($appointment->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel a non-pending appointment.');
        }

        $appointment->status = 'canceled';
        $appointment->save();

        $customer = User::find($appointment->user_id);
        if ($customer) {
            NotificationFacade::send($customer, new AppointmentCanceledNotification($appointment));
        }
        NotificationFacade::send($user, new AppointmentCanceledNotification($appointment));

        return redirect()->back()->with('success', 'Appointment canceled successfully!');
    }

    public function complete($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $user = Auth::user();

        if (!$user->companies->pluck('id')->contains($appointment->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($appointment->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Cannot complete a non-confirmed appointment.');
        }

        $appointment->status = 'completed';
        $appointment->save();

        // Thêm thông báo nếu cần
        $customer = User::find($appointment->user_id);
        if ($customer) {
            NotificationFacade::send($customer, new AppointmentCompletedNotification($appointment));
        }
        NotificationFacade::send($user, new AppointmentCompletedNotification($appointment));

        return redirect()->back()->with('success', 'Appointment completed successfully!');
    }

    public function show($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $user = Auth::user();

        if (!$user->companies->pluck('id')->contains($appointment->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $pageTitle = 'Chi tiết lịch hẹn';

        return view(activeTemplate() . 'user.company_appointment_details', compact('appointment', 'pageTitle', 'user'));
    }
}