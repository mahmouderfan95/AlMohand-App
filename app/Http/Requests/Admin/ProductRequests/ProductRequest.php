<?php

namespace App\Http\Requests\Admin\ProductRequests;

use App\Rules\RequiredProductOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $id = 0;

        if ($this->route('product'))
            $id = $this->route('product');

        return [
            'name.*' => "required|string",
            'category_ids' => "required|array",
            'category_ids.*' => "required|exists:categories,id",
            'vendor_id' => "nullable|exists:vendors,id",
            'brand_id' => "required|exists:brands,id",
            'type' => "required",
            'serial' => "sometimes|string",
            'quantity' => "sometimes|integer",
            'cost_price' => "sometimes|numeric",
            'wholesale_price' => "sometimes|numeric|gte:cost_price",
            'sku' => "sometimes|numeric",
            'notify' => "sometimes|integer",
            'max_quantity' => "sometimes|integer",
            'minimum_quantity' => "sometimes|integer|lt:max_quantity",
            'sort_order' =>  "sometimes|integer",
            'is_live_integration' =>  "sometimes|in:0,1",
        ];
    }

    public function messages()
    {
        return [
            '*.required' => trans("admin.general_validation.required"),
            "*.exists" => trans("admin.general_validation.exists"),
            'minimum_quantity.lt' => 'The minimum quantity must be less than the maximum quantity.',
            'cost_price.lt' => 'The cost price must be less than the wholesale price and the price.',
        ];
    }
}
