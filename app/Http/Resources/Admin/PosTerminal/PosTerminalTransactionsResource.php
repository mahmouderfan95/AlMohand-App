<?php

namespace App\Http\Resources\Admin\PosTerminal;

use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Http\Resources\Admin\BaseAdminResource;
use App\Models\POSTerminal\PosTerminal;
use App\Models\User;
use Illuminate\Http\Request;

class PosTerminalTransactionsResource extends BaseAdminResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'sales_rep' => $this?->distributor?->sales_rep,
            'transaction_code' => $this->transaction_code,
            'date' => $this->transaction_date?->format('Y-m-d - h:i:s'),
            'description' => $this->description ?: "",
            'amount' => $this->amount,
            //'currency_code' => $this->currency_code,
            'currency_code' => "رس",
            'type' => $this->type && $this->type == "credit" ? 'إضافة' : 'سحب',
            'transaction_type' => $this->type ,
            'created_by' => $this->created_by_type  == 'admin'  ? User::query()->find($this->created_by)?->name  : 'POS',
            'payment_method' => $this->payment_method ?? "الرصيد",
        ];
    }
}
