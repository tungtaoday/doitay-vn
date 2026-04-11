<?php

namespace App\Http\Requests\V1\Public;

use Illuminate\Foundation\Http\FormRequest;

class ListCompaniesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q'        => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'location' => ['nullable', 'integer'], // exists check left optional — vietnam_districts table name varies
            'page'     => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
