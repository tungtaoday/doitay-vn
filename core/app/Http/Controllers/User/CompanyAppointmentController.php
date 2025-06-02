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
        $pageTitle = 'Company Appointments';
        $user = Auth::user();
        $appointments = Appointment::whereIn('company_id', $user->companies->pluck('id'))
            ->with('company')
            ->get();

        return view('Template::user.companyAppointment', compact('pageTitle', 'appointments', 'user'));        // Bỏ 'user' nếu không dùng trong view
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

        $pageTitle = 'Appointment Details';
        $isCompany = true; // Đây là User

        return view('Template::user.appointment_show', compact('appointment', 'pageTitle', 'isCompany','user'));    }
}