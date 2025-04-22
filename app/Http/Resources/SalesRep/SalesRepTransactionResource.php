<?php

namespace App\Http\Resources\SalesRep;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\SalesRepUserResource;

class SalesRepTransactionResource extends JsonResource
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
            'balance_type' => $this->balance_type,
            'transaction_type' => $this->transaction_type,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'sales_rep_id' => $this->sales_rep_id,
            'approved_by' => $this->approvedBy ? [
                'id' => $this->approvedBy->id,
                'name' => $this->approvedBy->name,
            ] : null
        ];
    }
}
