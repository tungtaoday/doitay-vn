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
        // `da_mo` = thợ đã tự mở hồ sơ trong Mini App, CTV chỉ khai nhận công:
        // hệ đã có sẵn tên/nghề/khu vực nên KHÔNG bắt CTV gõ lại, chỉ cần SĐT để
        // khớp hồ sơ + ảnh bằng chứng đã gặp thợ (ảnh chụp chung / ảnh đoạn chat).
        $daMo = $this->input('loai') === 'da_mo';

        return [
            'loai'      => 'nullable|in:lam_ho,da_mo',
            'ten_tho'   => ($daMo ? 'nullable' : 'required') . '|string|max:100',
            'nghe'      => ($daMo ? 'nullable' : 'required') . '|string|max:100',
            'khu_vuc'   => ($daMo ? 'nullable' : 'required') . '|string|max:150',
            'sdt_tho'   => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s]{8,15}$/'],
            'nam_kn'    => 'nullable|integer|min:0|max:80',

            'bang_gia'       => 'nullable|array|max:20',
            'bang_gia.*.ten' => 'required_with:bang_gia|string|max:100',
            'bang_gia.*.gia' => 'required_with:bang_gia|string|max:50',

            'images'   => 'required|array|min:' . ($daMo ? 1 : 3) . '|max:5',
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
            'images.required'  => $this->input('loai') === 'da_mo'
                ? 'Cần ít nhất 1 ảnh bằng chứng đã gặp thợ (ảnh chụp chung hoặc ảnh đoạn chat).'
                : 'Vui lòng thêm ảnh công việc.',
            'images.min'       => $this->input('loai') === 'da_mo'
                ? 'Cần ít nhất 1 ảnh bằng chứng.'
                : 'Cần tối thiểu 3 ảnh công việc.',
            'images.max'       => 'Tối đa 5 ảnh.',
            'images.*.image'   => 'Tệp đính kèm phải là ảnh.',
            'images.*.max'     => 'Mỗi ảnh không được vượt quá 3MB.',
        ];
    }
}
