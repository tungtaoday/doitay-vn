<?php

namespace App\Http\Requests\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate lý do từ chối hồ sơ. Ref: DUC-SUBMISSION-REJECT (BR-SUB-R2).
 */
class RejectSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ly_do' => 'required|string|min:3|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'ly_do.required' => 'Vui lòng nhập lý do từ chối.',
            'ly_do.min'      => 'Lý do quá ngắn.',
        ];
    }
}
