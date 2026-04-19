<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:120'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'category_id'   => ['required', 'integer', 'exists:categories,id'],
            'description'   => ['required', 'string', 'min:50', 'max:5000'],
            'experience'    => ['required', 'integer', 'min:0', 'max:100'],

            'city_code'     => ['required', 'string'],
            'district_code' => ['required', 'string'],
            'ward_code'     => ['nullable', 'string'],
            'address'       => ['required', 'string', 'max:255'],

            'tags'          => ['nullable', 'array', 'max:20'],
            'tags.*'        => ['string', 'max:50'],

            'services'              => ['nullable', 'array', 'max:30'],
            'services.*.name'       => ['required_with:services', 'string', 'max:255'],
            'services.*.description'=> ['nullable', 'string', 'max:500'],
            'services.*.price'      => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Vui lòng nhập tên công ty.',
            'email.required'         => 'Vui lòng nhập email.',
            'email.email'            => 'Email không hợp lệ.',
            'category_id.required'   => 'Vui lòng chọn ngành nghề.',
            'category_id.exists'     => 'Ngành nghề không tồn tại.',
            'description.required'   => 'Vui lòng nhập mô tả.',
            'description.min'        => 'Mô tả phải có ít nhất 50 ký tự.',
            'experience.required'    => 'Vui lòng nhập số năm kinh nghiệm.',
            'city_code.required'     => 'Vui lòng chọn tỉnh/thành.',
            'district_code.required' => 'Vui lòng chọn quận/huyện.',
            'address.required'       => 'Vui lòng nhập địa chỉ cụ thể.',
        ];
    }
}
