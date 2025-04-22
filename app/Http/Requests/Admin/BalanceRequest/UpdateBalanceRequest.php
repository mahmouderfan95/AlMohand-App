<?php

namespace App\Http\Requests\Admin\BalanceRequest;

use App\Enums\BalanceRequest\BalanceRequestStatusEnum;
use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Enums\PosTerminal\PosTerminalBrandEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateBalanceRequest extends BaseRequest
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
            'status' => 'required|string|' . Rule::in([BalanceRequestStatusEnum::ACCEPTED, BalanceRequestStatusEnum::REJECTED]),
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Invalid status.',
        ];
    }
}
