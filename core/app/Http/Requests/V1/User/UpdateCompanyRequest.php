<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'required', 'string', 'max:255'],
            'email'         => ['sometimes', 'required', 'string', 'max:120'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'category_id'   => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'description'   => ['sometimes', 'required', 'string', 'min:50', 'max:5000'],
            'experience'    => ['sometimes', 'required', 'integer', 'min:0', 'max:100'],

            'city_code'     => ['sometimes', 'required', 'string'],
            'district_code' => ['sometimes', 'required', 'string'],
            'ward_code'     => ['nullable', 'string'],
            'address'       => ['sometimes', 'required', 'string', 'max:255'],

            'tags'               => ['nullable', 'array', 'max:20'],
            'tags.*'             => ['string', 'max:50'],

            'services'               => ['nullable', 'array', 'max:30'],
            'services.*.name'        => ['required_with:services', 'string', 'max:255'],
            'services.*.description' => ['nullable', 'string', 'max:500'],
            'services.*.price'       => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Vui lòng nhập tên.',
            'email.required'         => 'Vui lòng nhập email.',
            'category_id.required'   => 'Vui lòng chọn ngành nghề.',
            'category_id.exists'     => 'Ngành nghề không tồn tại.',
            'description.required'   => 'Vui lòng nhập mô tả.',
            'description.min'        => 'Mô tả phải có ít nhất 50 ký tự.',
            'experience.required'    => 'Vui lòng nhập số năm kinh nghiệm.',
            'city_code.required'     => 'Vui lòng chọn tỉnh/thành.',
            'district_code.required' => 'Vui lòng chọn quận/huyện.',
            'address.required'       => 'Vui lòng nhập địa chỉ.',
        ];
    }
}
