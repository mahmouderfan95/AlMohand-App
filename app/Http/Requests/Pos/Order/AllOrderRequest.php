<?php

namespace App\Http\Requests\Pos\Order;

use App\Enums\Order\OrderPaymentMethod;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;


class AllOrderRequest extends BaseRequest
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
            'date_from' => 'nullable|required_with:date_time_to|date:Y-m-d|before_or_equal:date_to',
            'date_to' => 'nullable|required_with:date_from|date:Y-m-d|after_or_equal:date_from',
            'search' => 'nullable|string',
        ];
    }
}
