<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateDepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'wallet_id' => ['required', 'integer', 'exists:company_wallets,id'],
            'payment_method_id' => ['required', 'integer', 'exists:deposit_settings,id'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'user_notes' => ['nullable', 'string', 'max:1000'],
            'payment_proof' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'idempotency_key' => ['nullable', 'string', 'max:120'],
        ];
    }
}
