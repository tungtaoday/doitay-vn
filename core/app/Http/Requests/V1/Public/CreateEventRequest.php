<?php

namespace App\Http\Requests\V1\Public;

use App\Models\ProductEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Ghi 1 product event (public, không auth). Chống spam bằng rate-limit ở route
 * + whitelist event. Không nhận PII (actor_key là zalo_id / session ẩn danh).
 */
class CreateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event'      => ['required', 'string', Rule::in(ProductEvent::ALLOWED)],
            'surface'    => ['nullable', Rule::in(['tho', 'khach'])],
            'channel'    => ['nullable', Rule::in(['miniapp', 'web'])],
            'company_id' => ['nullable', 'integer', 'min:1'],
            'actor_key'  => ['nullable', 'string', 'max:191'],
            'meta'       => ['nullable', 'array'],
        ];
    }
}
