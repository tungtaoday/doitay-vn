<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // identifier = email HOẶC số điện thoại VN. AuthService::login tự phát
        // hiện loại qua App\Support\Identifier, nên ở đây chỉ cần string.
        return [
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required' => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required'   => 'Vui lòng nhập mật khẩu.',
        ];
    }
}
