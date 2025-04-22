<?php

namespace App\Http\Requests\Admin\Distributor;

use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateBalanceRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(TransactionTypeEnum::cases())],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'max:255'],
        ];
    }
}
