<?php

namespace App\Http\Requests\V1\Public;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Tải ảnh công việc của thợ lên server (Mini App).
 * Giới hạn để cửa công khai không thành chỗ chứa file: 5 ảnh/lần, 4MB/ảnh.
 */
class UploadThoImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images'   => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'titles'   => ['nullable', 'array', 'max:5'],
            'titles.*' => ['nullable', 'string', 'max:120'],
            // Quyền sửa: chủ hồ sơ (zalo_id) hoặc người cầm vé claim còn hạn.
            'zalo_id'  => ['nullable', 'string', 'max:64'],
            'token'    => ['nullable', 'string', 'max:64'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Chưa chọn ảnh nào.',
            'images.max'      => 'Mỗi lần tải tối đa 5 ảnh.',
            'images.*.max'    => 'Ảnh quá nặng (tối đa 4MB) — app nên nén trước khi gửi.',
            'images.*.mimes'  => 'Chỉ nhận ảnh JPG, PNG hoặc WEBP.',
        ];
    }
}
