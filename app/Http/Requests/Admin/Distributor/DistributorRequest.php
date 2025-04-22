<?php

namespace App\Http\Requests\Admin\Distributor;

use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class DistributorRequest extends BaseRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        $isUpdate = !is_null($id);

        return [
            'distributor_group_id' => 'nullable|integer|exists:distributor_group,id',
            'zone_id' => 'required|integer|exists:zone,id',
            'city_id' => 'required|integer|exists:cities,id',
            'sales_rep_id' => 'required|integer|exists:sales_reps,id',
            'manager_name' => 'required|string',
            'owner_name' => 'required|string',
            'phone' => [
                'required',
                'string',
                Rule::unique('distributor', 'phone')->ignore($id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('distributor', 'email')->ignore($id),
            ],
            'address' => 'nullable|string',
            'commercial_register' => 'required|string',
            'tax_card' => 'required|string',
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|' . Rule::unique('distributor_translations', 'name')->ignore($id, 'distributor_id'),
            'translations.*.commercial_register_name' => 'required|string',
            'logo' => 'nullable|image|mimes:png,jpg|max:2048',
            'identity_files' => [$isUpdate ? 'nullable' : 'required', 'array'],
            'identity_files.*' => 'file|max:1048576|mimes:jpeg,png,jpg,pdf',
            'commercial_register_files' => [$isUpdate ? 'nullable' : 'required', 'array'],
            'commercial_register_files.*' => 'file|max:1048576|mimes:jpeg,png,jpg,pdf'
        ];
    }
}
