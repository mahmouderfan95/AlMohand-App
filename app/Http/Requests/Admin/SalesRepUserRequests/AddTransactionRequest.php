<?php

namespace App\Http\Requests\Admin\SalesRepUserRequests;

use Illuminate\Foundation\Http\FormRequest;

class AddTransactionRequest extends FormRequest
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
            'transaction_type' => 'required|in:credit,debit',
            'amount' =>  'required|numeric|min:1',
            'balance_type' =>  'required|string',
            'sales_rep_id' => ['nullable', 'exists:sales_reps,id'],
        ];
    }

    /*
    public function messages()
    {

        return [
            'name.*.required' => trans("validation.required"),
            "description.required" => trans("validation.required"),
        ];

    }
    */
}
