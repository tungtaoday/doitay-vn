<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s]{8,15}$/'],
            'recipient_address' => 'required|string|max:500',
            'appointment_date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(60)->toDateString(),
            'appointment_time' => ['required', 'string', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'notes' => 'nullable|string|max:1000',
            'service_request_id' => 'nullable|integer|exists:service_requests,id',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Vui lòng chọn thợ.',
            'recipient_name.required' => 'Vui lòng nhập họ tên.',
            'recipient_phone.required' => 'Vui lòng nhập số điện thoại.',
            'recipient_phone.regex' => 'Số điện thoại không hợp lệ.',
            'recipient_address.required' => 'Vui lòng nhập địa chỉ.',
            'appointment_date.required' => 'Vui lòng chọn ngày hẹn.',
            'appointment_date.after_or_equal' => 'Ngày hẹn phải từ hôm nay trở đi.',
            'appointment_date.before_or_equal' => 'Ngày hẹn không thể xa quá 60 ngày.',
            'appointment_time.required' => 'Vui lòng chọn giờ hẹn.',
            'appointment_time.regex' => 'Giờ hẹn không đúng định dạng HH:MM.',
        ];
    }

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($v) {
            $date = $this->input('appointment_date');
            $time = $this->input('appointment_time');
            if (! $date || ! $time) {
                return;
            }
            try {
                $slot = \Carbon\Carbon::parse($date . ' ' . $time);
            } catch (\Throwable) {
                return;
            }
            if ($slot->lt(now()->addHours(2))) {
                $v->errors()->add('appointment_time', 'Lịch hẹn phải cách hiện tại ít nhất 2 giờ.');
            }
        });
    }
}
