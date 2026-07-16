<?php

namespace App\Http\Requests\V1\Auth;

use App\Models\User;
use App\Support\Identifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // identifier = email HOẶC số điện thoại VN (thống nhất với login).
        // Kiểm tra trùng theo đúng cột (email/mobile) để trả 422 thay vì 500.
        return [
            'firstname'  => ['nullable', 'string', 'max:100'],
            'lastname'   => ['nullable', 'string', 'max:100'],
            'name'       => ['required', 'string', 'min:2', 'max:100'],
            'identifier' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $type = Identifier::detect((string) $value);
                    if ($type === null) {
                        $fail('Email hoặc số điện thoại không hợp lệ.');
                        return;
                    }
                    if ($type === Identifier::TYPE_EMAIL) {
                        if (User::where('email', strtolower((string) $value))->exists()) {
                            $fail('Email này đã được sử dụng.');
                        }
                    } elseif (User::where('mobile', Identifier::normalizePhone((string) $value))->exists()) {
                        $fail('Số điện thoại này đã được sử dụng.');
                    }
                },
            ],
            'password'   => ['required', 'string', 'confirmed', Password::min(8)],
            'user_role'  => ['nullable', 'in:customer,contractor,both'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Vui lòng nhập họ tên.',
            'identifier.required' => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required'   => 'Vui lòng nhập mật khẩu.',
            'password.confirmed'  => 'Mật khẩu xác nhận không khớp.',
        ];
    }
}
