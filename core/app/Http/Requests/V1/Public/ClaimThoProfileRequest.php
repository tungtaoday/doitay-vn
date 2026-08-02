<?php

namespace App\Http\Requests\V1\Public;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Thợ bấm link CTV gửi để nhận hồ sơ đã được dựng hộ.
 * Vé (`token`) dùng 1 lần, có hạn — xem MiniAppProfileService::claim().
 */
class ClaimThoProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'   => ['required', 'string', 'max:64'],
            'zalo_id' => ['required', 'string', 'max:64'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required'   => 'Thiếu mã nhận hồ sơ.',
            'zalo_id.required' => 'Không lấy được tài khoản Zalo.',
        ];
    }
}
