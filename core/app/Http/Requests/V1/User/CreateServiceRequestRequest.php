<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|min:5|max:160',
            'description' => 'required|string|min:20|max:2000',

            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',

            'budget_min' => 'nullable|integer|min:0',
            'budget_max' => 'nullable|integer|min:0|gte:budget_min',

            'preferred_date' => 'nullable|date|after_or_equal:today|before_or_equal:' . now()->addDays(60)->toDateString(),
            'preferred_time_slot' => 'nullable|in:morning,afternoon,evening,flexible',

            'contact_name' => 'required|string|max:100',
            'contact_phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s]{8,15}$/'],

            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:3072',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục dịch vụ.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.min' => 'Tiêu đề tối thiểu 5 ký tự.',
            'description.required' => 'Vui lòng mô tả công việc.',
            'description.min' => 'Mô tả tối thiểu 20 ký tự.',
            'city.required' => 'Vui lòng chọn tỉnh/thành.',
            'budget_max.gte' => 'Ngân sách tối đa phải lớn hơn hoặc bằng ngân sách tối thiểu.',
            'contact_phone.regex' => 'Số điện thoại không hợp lệ.',
            'images.max' => 'Tối đa 5 ảnh đính kèm.',
            'images.*.image' => 'Tệp đính kèm phải là ảnh.',
            'images.*.max' => 'Mỗi ảnh không được vượt quá 3MB.',
        ];
    }
}
