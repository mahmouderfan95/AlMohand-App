<?php

namespace App\Http\Requests\Admin\OrderRequests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'seller_id' => 'required|integer',
            'payment_method' => 'required|string',
            'order_products' => 'required|array',
            'order_products.*.product_id' => 'required|integer',
            'order_products.*.category_id' => 'required|integer',
            'order_products.*.quantity' => 'required',

            // 'order_products.*.product_options' => 'nullable|array|required_if:type,2',
            // 'order_products.*.product_options.*.id' => 'required|integer',
            // 'order_products.*.product_options.*.option_value_ids' => 'required_if:product_options.*.value,null|nullable|array',
            // 'order_products.*.product_options.*.value' => 'required_if:product_options.*.option_value_ids,null|nullable|string',

        ];
    }

    public function messages()
    {
        return [
            '*.required' => trans("admin.general_validation.required"),
        ];
    }
}
