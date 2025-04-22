<?php

namespace App\Http\Requests\Pos\BalanceRequest;

use App\Http\Requests\BaseRequest;


class MadaCallbackRequest extends BaseRequest
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
            'payment_code' => ['required', 'string', 'size:14'],
            'payment_gateway' => ['required', 'string', 'max:10'],
        ];
    }
}
