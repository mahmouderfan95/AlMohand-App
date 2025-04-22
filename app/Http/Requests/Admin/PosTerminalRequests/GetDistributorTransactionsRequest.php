<?php

namespace App\Http\Requests\Admin\PosTerminalRequests;

use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Enums\PosTerminal\PosTerminalBrandEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class GetDistributorTransactionsRequest extends BaseRequest
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
            'filters' => ['nullable', 'array'],
            'filters.description' => ['nullable', 'string'],
            'filters.type' => ['nullable', 'in:credit,debit'],
            'filters.amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
