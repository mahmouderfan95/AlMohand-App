<?php

namespace App\Http\Requests\Admin\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\Rules\Phone;

class PointsCommissionSettingRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'apply_on_selling_by_mada'    => 'required|boolean',
            'apply_on_recharging_by_mada' => 'required|boolean',
            'amount_per_points_redeem'    => 'required|numeric|min:0',
            'points_per_amount_redeem'    => 'required|numeric|min:0',
            'amount_per_points'           => 'required|numeric|min:0',
            'points_per_amount'           => 'required|numeric|min:0',
            'mada_fees'                   => 'required|numeric|min:0',
            'mada_added_tax'              => 'required|numeric|min:0',
        ];
    }
}
