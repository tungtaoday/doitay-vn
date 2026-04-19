<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'          => 'sometimes|required|string|max:255',
            'username'      => "sometimes|required|string|min:6|max:50|alpha_dash|unique:users,username,{$userId}",
            'mobile'        => 'sometimes|required|regex:/^([0-9]{10,11})$/',
            'about'         => 'nullable|string|max:1000',
            'city_code'     => 'sometimes|required|string',
            'district_code' => 'sometimes|required|string',
            'ward_code'     => 'sometimes|required|string',
            'address'       => 'sometimes|required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Vui lòng nhập họ tên.',
            'username.required'      => 'Vui lòng nhập tên đăng nhập.',
            'username.min'           => 'Tên đăng nhập phải ít nhất 6 ký tự.',
            'username.alpha_dash'    => 'Tên đăng nhập chỉ được dùng chữ cái, số, gạch ngang và gạch dưới.',
            'username.unique'        => 'Tên đăng nhập đã tồn tại.',
            'mobile.required'        => 'Vui lòng nhập số điện thoại.',
            'mobile.regex'           => 'Số điện thoại phải có 10-11 chữ số.',
            'city_code.required'     => 'Vui lòng chọn tỉnh/thành.',
            'district_code.required' => 'Vui lòng chọn quận/huyện.',
            'ward_code.required'     => 'Vui lòng chọn phường/xã.',
            'address.required'       => 'Vui lòng nhập địa chỉ.',
        ];
    }
}
