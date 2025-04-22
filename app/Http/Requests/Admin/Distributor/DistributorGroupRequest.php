<?php

namespace App\Http\Requests\Admin\Distributor;

use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class DistributorGroupRequest extends BaseRequest
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
        return [
            'is_active' => 'required|boolean',
            'is_auto_assign' => 'required|boolean',
            'is_require_all_conditions' => 'nullable|boolean',
            'translations' => 'required|array',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'conditions.*.condition_type' => 'required|in:' . implode(',', DistributorConditionTypeEnum::getList()),
            'conditions.*.prefix' => 'required|in:' . implode(',', DistributorConditionPrefixEnum::getList()),
            'conditions.*.value' => 'required|string',
        ];
    }
}
