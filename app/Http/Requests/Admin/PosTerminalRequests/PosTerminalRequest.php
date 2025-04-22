<?php

namespace App\Http\Requests\Admin\PosTerminalRequests;

use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Enums\PosTerminal\PosTerminalBrandEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class PosTerminalRequest extends BaseRequest
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
        return [
            'name' => [
                $id ? 'nullable' : 'required', // Required if no ID (create), nullable if ID exists (update)
                'string',
                Rule::unique('pos_terminal', 'name')->ignore($id)
            ],
            'brand' => 'required|string|' . Rule::in(PosTerminalBrandEnum::getList()),
            'serial_number' => ['required', 'string', Rule::unique('pos_terminal', 'serial_number')->ignore($id)],
            'terminal_id' => ['required', 'string', Rule::unique('pos_terminal', 'terminal_id')->ignore($id)],
        ];
    }
}
