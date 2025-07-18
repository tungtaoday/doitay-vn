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

            // Send email to customer using auto flow with proper shortcodes
            notify($user, 'NEW_APPOINTMENT', [
                'user_name' => $user->fullname ?: ($user->firstname . ' ' . $user->lastname) ?: $user->username ?: $user->name,
                'user_email' => $user->email,
                'appointment_id' => $appointment->id,
                'appointment_date' => date('d/m/Y', strtotime($appointment->appointment_date)),
                'appointment_time' => $appointment->appointment_time,
                'company_name' => $appointment->company->name ?? 'Service Provider',
                'company_phone' => $appointment->company->mobile ?? $appointment->company->phone ?? 'Sẽ cập nhật sau',
                'appointment_address' => $appointment->recipient_address,
                'site_url' => url('/'),
                'current_year' => date('Y'),
                'notes' => $appointment->notes ?? 'Không có ghi chú đặc biệt'
            ]);

            // Send in-app notification to customer
            NotificationService::sendAppointmentNotification($user, $appointment, 'appointment_created');

            // Send email to company owner using auto flow with proper shortcodes
            $companyOwner = $appointment->company->user;
            if ($companyOwner) {
                notify($companyOwner, 'NEW_APPOINTMENT', [
                    'user_name' => $companyOwner->fullname ?: ($companyOwner->firstname . ' ' . $companyOwner->lastname) ?: $companyOwner->username ?: $companyOwner->name,
                    'user_email' => $companyOwner->email,
                    'appointment_id' => $appointment->id,
                    'appointment_date' => date('d/m/Y', strtotime($appointment->appointment_date)),
                    'appointment_time' => $appointment->appointment_time,
                    'company_name' => $appointment->company->name ?? 'Service Provider',
                    'company_phone' => $appointment->company->mobile ?? $appointment->company->phone ?? 'Sẽ cập nhật sau',
                    'appointment_address' => $appointment->recipient_address,
                    'site_url' => url('/'),
                    'current_year' => date('Y'),
                    'notes' => $appointment->notes ?? 'Không có ghi chú đặc biệt'
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

        // Send email to customer using auto flow with proper shortcodes
        notify($user, 'APPOINTMENT_CANCELED', [
            'user_name' => $user->fullname ?: ($user->firstname . ' ' . $user->lastname) ?: $user->username ?: $user->name,
            'user_email' => $user->email,
            'appointment_id' => $appointment->id,
            'appointment_date' => date('d/m/Y', strtotime($appointment->appointment_date)),
            'appointment_time' => $appointment->appointment_time,
            'company_name' => $appointment->company->name ?? 'Service Provider',
            'company_phone' => $appointment->company->mobile ?? $appointment->company->phone ?? 'Sẽ cập nhật sau',
            'appointment_address' => $appointment->recipient_address,
            'site_url' => url('/'),
            'current_year' => date('Y'),
            'notes' => $appointment->notes ?? 'Không có ghi chú đặc biệt'
        ]);

        // Send in-app notification to customer
        NotificationService::sendAppointmentNotification($user, $appointment, 'appointment_cancelled');

        // Send email to company owner using auto flow with proper shortcodes
        $companyOwner = $appointment->company->user;
        if ($companyOwner) {
            notify($companyOwner, 'APPOINTMENT_CANCELED', [
                'user_name' => $companyOwner->fullname ?: ($companyOwner->firstname . ' ' . $companyOwner->lastname) ?: $companyOwner->username ?: $companyOwner->name,
                'user_email' => $companyOwner->email,
                'appointment_id' => $appointment->id,
                'appointment_date' => date('d/m/Y', strtotime($appointment->appointment_date)),
                'appointment_time' => $appointment->appointment_time,
                'company_name' => $appointment->company->name ?? 'Service Provider',
                'company_phone' => $appointment->company->mobile ?? $appointment->company->phone ?? 'Sẽ cập nhật sau',
                'appointment_address' => $appointment->recipient_address,
                'site_url' => url('/'),
                'current_year' => date('Y'),
                'notes' => $appointment->notes ?? 'Không có ghi chú đặc biệt'
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

    // Đánh giá appointment sau khi hoàn thành
    public function submitReview(Request $request, $appointmentId)
    {
        $request->validate([
            'rating' => 'required|array', // Yêu cầu phải có mảng rating
            'rating.*' => 'required|integer|min:1|max:5', // Mỗi rating phải là số hợp lệ
            'comment' => 'required|string|max:1000'
        ]);

        $appointment = Appointment::with('company')->findOrFail($appointmentId);
        $user = Auth::user();

        // Kiểm tra quyền đánh giá
        if ($appointment->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền đánh giá lịch hẹn này.'], 403);
        }

        // Kiểm tra trạng thái lịch hẹn
        if ($appointment->status !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể đánh giá lịch hẹn đã hoàn thành.'], 400);
        }

        // Kiểm tra đã đánh giá chưa
        $existingRating = \App\Models\Rating::where('user_id', $user->id)
            ->where('company_id', $appointment->company_id)
            ->where('appointment_id', $appointment->id)
            ->first();

        if ($existingRating) {
            return response()->json(['success' => false, 'message' => 'Bạn đã đánh giá lịch hẹn này rồi.'], 400);
        }

        // Tạo đánh giá mới
        $rating = \App\Models\Rating::create([
            'user_id' => $user->id,
            'company_id' => $appointment->company_id,
            'appointment_id' => $appointment->id,
            'suggest' => $request->comment,
            'status' => 1
        ]);

        // Xóa các rating_detail cũ liên quan đến rating này (nếu có)
        \App\Models\RatingDetail::where('rating_id', $rating->id)->delete();

        // Lưu thông tin từng feature vào rating_details
        foreach ($request->rating as $featureId => $score) {
            \App\Models\RatingDetail::create([
                'rating_id' => $rating->id,
                'feature_id' => $featureId,
                'rating' => (float)$score,
            ]);
        }

        // Tính lại avg_rating cho bản ghi ratings
        $avgRating = \App\Models\RatingDetail::where('rating_id', $rating->id)->avg('rating');

        // Cập nhật avg_rating vào bảng ratings
        $rating->avg_rating = round($avgRating, 2);
        $rating->save();

        // Tính lại avg_rating của công ty từ bảng rating_details
        $averageRating = \App\Models\RatingDetail::join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
            ->where('ratings.company_id', $appointment->company_id)
            ->avg('rating_details.rating');

        // Cập nhật avg_rating vào bảng company
        $company = $appointment->company;
        $company->avg_rating = $averageRating ? round($averageRating, 2) : 0;
        $company->save();

        return response()->json([
            'success' => true, 
            'message' => 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn sẽ giúp cải thiện chất lượng dịch vụ.'
        ]);
    }

    // Lấy category_id của company từ appointment
    public function getAppointmentCompanyCategory($appointmentId)
    {
        try {
            $appointment = Appointment::with('company')->findOrFail($appointmentId);
            $user = Auth::user();

            // Kiểm tra quyền xem
            if ($appointment->user_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Không có quyền truy cập.'], 403);
            }

            return response()->json([
                'success' => true,
                'category_id' => $appointment->company->category_id ?? null,
                'company_name' => $appointment->company->name ?? 'N/A'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin appointment.'
            ], 404);
        }
    }

    // Các phương thức không cần cho User: confirm, complete
    // (Chuyển sang CompanyAppointmentController)
}