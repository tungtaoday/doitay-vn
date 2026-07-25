<?php

namespace App\Http\Requests\V1\Public;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Tạo hồ sơ thợ từ Zalo Mini App (self-serve). Public — chống spam bằng: SĐT
 * dedup, hàng đợi duyệt, rate-limit ở route. Nâng cấp Zalo getPhoneNumber sau.
 */
class CreateThoProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:120'],
            'phone'       => ['required', 'string', 'max:20'],
            'nghe'        => ['required', 'string', 'max:120'],
            'city'        => ['nullable', 'string', 'max:120'],
            'district'    => ['nullable', 'string', 'max:120'],
            'experience'  => ['nullable', 'integer', 'min:0', 'max:80'],
            'description' => ['nullable', 'string', 'max:2000'],
            'tags'        => ['nullable', 'array', 'max:20'],
            'tags.*'      => ['string', 'max:60'],
            'services'    => ['nullable', 'array', 'max:30'],
            'services.*.name'  => ['required_with:services', 'string', 'max:120'],
            'services.*.unit'  => ['nullable', 'string', 'max:40'],
            'services.*.price' => ['nullable', 'string', 'max:40'],
            'zalo_id'     => ['nullable', 'string', 'max:64'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Vui lòng nhập tên thợ.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'nghe.required'  => 'Vui lòng chọn nghề.',
        ];
    }
}
