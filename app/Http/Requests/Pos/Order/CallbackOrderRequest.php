<?php

namespace App\Http\Requests\Pos\Order;

use App\Enums\Order\OrderPaymentMethod;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;


class CallbackOrderRequest extends BaseRequest
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
            'order_id' => ['required', 'integer'],
            'mada_response' => ['required', 'array'],
        ];
    }
}
