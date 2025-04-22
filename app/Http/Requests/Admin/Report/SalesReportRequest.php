<?php

namespace App\Http\Requests\Admin\Report;

use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Enums\PosTerminal\PosTerminalBrandEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class SalesReportRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->query());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'distributor_id' => ['nullable', 'uuid', 'exists:distributor,id'],
            'print_status' => ['nullable', 'integer', 'in:0,1'],
            'payment_method' => ['nullable', 'string', 'in:mada,balance,cash,credit_card'],
        ];
    }
}
