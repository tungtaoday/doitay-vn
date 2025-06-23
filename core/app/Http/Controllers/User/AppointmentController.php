<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Notifications\NewAppointmentNotification;
use App\Notifications\AppointmentConfirmedNotification;
use App\Notifications\AppointmentCanceledNotification;
use App\Notifications\AppointmentCompletedNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class AppointmentController extends Controller
{
    // Kiểm tra email có tồn tại không
    public function checkEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $emailExists]);
    }

    // Đặt lịch hẹn (User hoặc khách)
    public function create(Request $request)
    {
        try {
            $user = Auth::user();
            $rules = [
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:15',
                'recipient_address' => 'required|string',
                'appointmentDate' => 'required|date',
                'appointmentTime' => 'required',
                'notes' => 'nullable|string',
                'company_id' => 'required|exists:companies,id',
            ];

            if (!$user) {
                $rules['email'] = 'required|email|unique:users,email';
            }

            $request->validate($rules);

            if (!$user) {
                $password = Str::random(8);
                $user = User::create([
                    'name' => $request->recipient_name,
                    'mobile' => $request->recipient_phone,
                    'email' => $request->email,
                    'password' => Hash::make($password),
                ]);

                Auth::login($user);
            }

            // Check if customer has already made appointment with this company
            $existingAppointment = Appointment::where('user_id', $user->id)
                ->where('company_id', $request->company_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingAppointment) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn đã có lịch hẹn với thợ này rồi!'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Bạn đã có lịch hẹn với thợ này rồi!');
            }

            $appointment = Appointment::create([
                'user_id' => $user->id,
                'company_id' => $request->company_id,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'appointment_date' => $request->appointmentDate,
                'appointment_time' => $request->appointmentTime,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Send email to customer using auto flow
            notify($user, 'NEW_APPOINTMENT', [
                'customer_name' => $appointment->recipient_name,
                'customer_phone' => $appointment->recipient_phone,
                'customer_address' => $appointment->recipient_address,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'notes' => $appointment->notes ?? 'N/A',
                'company_name' => $appointment->company->name ?? 'Unknown Company'
            ]);

            // Send in-app notification to customer
            NotificationService::sendAppointmentNotification($user, $appointment, 'appointment_created');

            // Send email to company owner using auto flow
            $companyOwner = $appointment->company->user;
            if ($companyOwner) {
                notify($companyOwner, 'NEW_APPOINTMENT', [
                    'customer_name' => $appointment->recipient_name,
                    'customer_phone' => $appointment->recipient_phone,
                    'customer_address' => $appointment->recipient_address,
                    'appointment_date' => $appointment->appointment_date,
                    'appointment_time' => $appointment->appointment_time,
                    'notes' => $appointment->notes ?? 'N/A',
                    'company_name' => $appointment->company->name ?? 'Unknown Company'
                ]);
                
                // Send in-app notification to company owner
                NotificationService::sendAppointmentNotification($companyOwner, $appointment, 'appointment_created');
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt lịch thành công! Thợ sẽ liên hệ với bạn sớm.',
                    'redirect' => route('appointment.success', ['id' => $appointment->id])
                ]);
            }

            return redirect()->route('appointment.success', ['id' => $appointment->id])->with('success', 'Đặt lịch thành công! Thợ sẽ liên hệ với bạn sớm.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra, vui lòng thử lại'
                ], 500);
            }
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    // Danh sách lịch hẹn của User
    public function index()
    {
        $pageTitle = 'Lịch hẹn của tôi';
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)
            ->with('company')
            ->latest()
            ->get();

        return view(activeTemplate() . 'user.appointments', compact('pageTitle', 'appointments', 'user'));
    }

    // Xem chi tiết lịch hẹn
    public function show($appointmentId)
    {
        $appointment = Appointment::with('company')->findOrFail($appointmentId);
        $user = Auth::user();

        if ($appointment->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $pageTitle = 'Chi tiết lịch hẹn';

        return view(activeTemplate() . 'user.appointment_details', compact('appointment', 'pageTitle', 'user'));
    }

    // Hủy lịch hẹn (User)
    public function cancel($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $user = Auth::user();

        if ($appointment->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel a non-pending appointment.');
        }

        $appointment->status = 'canceled';
        $appointment->save();

        // Send email to customer using auto flow
        notify($user, 'APPOINTMENT_CANCELED', [
            'customer_name' => $appointment->recipient_name,
            'customer_phone' => $appointment->recipient_phone,
            'customer_address' => $appointment->recipient_address,
            'appointment_date' => $appointment->appointment_date,
            'appointment_time' => $appointment->appointment_time,
            'notes' => $appointment->notes ?? 'N/A',
            'company_name' => $appointment->company->name ?? 'Unknown Company'
        ]);

        // Send in-app notification to customer
        NotificationService::sendAppointmentNotification($user, $appointment, 'appointment_cancelled');

        // Send email to company owner using auto flow
        $companyOwner = $appointment->company->user;
        if ($companyOwner) {
            notify($companyOwner, 'APPOINTMENT_CANCELED', [
                'customer_name' => $appointment->recipient_name,
                'customer_phone' => $appointment->recipient_phone,
                'customer_address' => $appointment->recipient_address,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'notes' => $appointment->notes ?? 'N/A',
                'company_name' => $appointment->company->name ?? 'Unknown Company'
            ]);
        }

        return redirect()->back()->with('success', 'Appointment canceled successfully!');
    }

    // Xác minh email cho khách
    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->ver_code === $request->ver_code) {
            $user->status = 1;
            $user->profile_complete = 1;
            $user->save();

            return redirect()->route('appointments.index')->with('success', 'Account verified successfully!');
        }

        return redirect()->back()->with('error', 'Invalid verification code.');
    }

    // Hiển thị trang success sau khi đặt lịch thành công
    public function success($appointmentId)
    {
        $appointment = Appointment::with('company')->findOrFail($appointmentId);
        $pageTitle = 'Đặt lịch thành công';
        
        return view(activeTemplate() . 'appointment_success', compact('appointment', 'pageTitle'));
    }

    // Các phương thức không cần cho User: confirm, complete
    // (Chuyển sang CompanyAppointmentController)
}