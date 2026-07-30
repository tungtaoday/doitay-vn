<?php

namespace App\Http\Requests\V1\Public;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Đặt lịch KHÔNG cần đăng nhập (guest). Khách nhập tên + SĐT (+ email tuỳ chọn);
 * hệ thống tự tìm-hoặc-tạo tài khoản theo SĐT/email rồi tạo lịch. Public — chống
 * spam bằng rate-limit ở route. Không nhận OTP (giảm ma sát) — thợ gọi SĐT xác nhận.
 */
class CreateGuestAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'        => 'required|integer|exists:companies,id',
            'recipient_name'    => 'required|string|max:255',
            'recipient_phone'   => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s]{8,15}$/'],
            'recipient_email'   => ['nullable', 'email', 'max:191'],
            'recipient_address' => 'required|string|max:500',
            'appointment_date'  => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(60)->toDateString(),
            'appointment_time'  => ['required', 'string', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'notes'             => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_name.required'  => 'Vui lòng nhập họ tên.',
            'recipient_phone.required' => 'Vui lòng nhập số điện thoại.',
            'recipient_phone.regex'    => 'Số điện thoại không hợp lệ.',
            'recipient_address.required' => 'Vui lòng nhập địa chỉ.',
            'appointment_date.required' => 'Vui lòng chọn ngày hẹn.',
            'appointment_time.required' => 'Vui lòng chọn giờ hẹn.',
        ];
    }
}
