<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class BalanceRechargeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recharge_balance_type' => 'required|string|in:cash,visa',
            'bank_name' => 'required_if:recharge_balance_type,cash|string|max:255',
            'transferring_name' => 'required|string|max:255',
            'receipt_image' => 'required_if:recharge_balance_type,cash|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
            'amount' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/', // Validates the amount as a double with up to 2 decimal places
        ],
        ];
    }
}
