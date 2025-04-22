<?php

namespace App\Http\Requests\Pos\Print;

use App\Enums\Order\OrderPaymentMethod;
use App\Http\Requests\BaseRequest;
use App\Models\Order\Order;
use Illuminate\Validation\Rule;


class DecreaseCountRequest extends BaseRequest
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
            'all_serials' => ['required', 'integer', Rule::in([0,1])],
            'order_product_serial_ids' => ['required', 'array', 'min:1'],
            'order_product_serial_ids.*' => ['required', 'integer', 'distinct', 'min:1'],
        ];
    }
}
