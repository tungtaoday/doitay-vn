<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class CompleteProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'username' => "required|string|min:6|unique:users,username,{$userId}",
            'mobile' => 'required|regex:/^([0-9]{10,11})$/',
            'city_code' => 'required|string',
            'district_code' => 'required|string',
            'ward_code' => 'required|string',
            'address' => 'required|string|max:255',
            'register_as_expert' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Vui lòng nhập tên người dùng.',
            'username.min' => 'Tên người dùng phải ít nhất 6 ký tự.',
            'username.unique' => 'Tên người dùng đã tồn tại.',
            'mobile.required' => 'Vui lòng nhập số điện thoại.',
            'mobile.regex' => 'Số điện thoại phải có 10-11 chữ số.',
            'city_code.required' => 'Vui lòng chọn tỉnh/thành.',
            'district_code.required' => 'Vui lòng chọn quận/huyện.',
            'ward_code.required' => 'Vui lòng chọn phường/xã.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
        ];
    }
}
