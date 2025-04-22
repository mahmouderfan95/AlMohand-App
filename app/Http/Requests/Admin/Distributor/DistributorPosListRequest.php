<?php

namespace App\Http\Requests\Admin\Distributor;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class DistributorPosListRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'merchant_id' => ['required', 'uuid', 'exists:distributor,id'],
            'filters' => ['nullable', 'array'],
            'filters.branch_name' => ['nullable', 'string'],
            'filters.address' => ['nullable', 'string'],
            'filters.receiver_phone' => ['nullable', 'string', 'regex:/^\d+$/'],
            'filters.receiver_name' => ['nullable', 'string'],
            'filters.pos_terminal_id' => ['nullable', 'uuid'],
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
