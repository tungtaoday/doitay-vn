<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ratings' => 'required|array|min:1',
            'ratings.*' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'ratings.required' => 'Vui lòng đánh giá ít nhất 1 tiêu chí.',
            'ratings.*.min' => 'Điểm đánh giá tối thiểu là 1.',
            'ratings.*.max' => 'Điểm đánh giá tối đa là 5.',
            'comment.required' => 'Vui lòng nhập nhận xét.',
        ];
    }
}
