<?php

namespace App\Http\Requests\V1\Sale;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate hồ sơ thợ CTV nhập. Ref: DUC-SUBMISSION-CREATE (BR-SUB-2).
 */
class CreateSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_tho'   => 'required|string|max:100',
            'nghe'      => 'required|string|max:100',
            'khu_vuc'   => 'required|string|max:150',
            'sdt_tho'   => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s]{8,15}$/'],
            'nam_kn'    => 'nullable|integer|min:0|max:80',

            'bang_gia'       => 'nullable|array|max:20',
            'bang_gia.*.ten' => 'required_with:bang_gia|string|max:100',
            'bang_gia.*.gia' => 'required_with:bang_gia|string|max:50',

            'images'   => 'required|array|min:3|max:5',
            'images.*' => 'image|max:3072',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_tho.required' => 'Vui lòng nhập tên thợ.',
            'nghe.required'    => 'Vui lòng nhập nghề.',
            'khu_vuc.required' => 'Vui lòng nhập khu vực.',
            'sdt_tho.required' => 'Vui lòng nhập số điện thoại thợ.',
            'sdt_tho.regex'    => 'Số điện thoại không hợp lệ.',
            'images.required'  => 'Vui lòng thêm ảnh công việc.',
            'images.min'       => 'Cần tối thiểu 3 ảnh công việc.',
            'images.max'       => 'Tối đa 5 ảnh.',
            'images.*.image'   => 'Tệp đính kèm phải là ảnh.',
            'images.*.max'     => 'Mỗi ảnh không được vượt quá 3MB.',
        ];
    }
}
