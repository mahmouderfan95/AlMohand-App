<?php

namespace App\Http\Resources\SalesRep\BalanceLog;

use App\Http\Resources\Admin\BaseAdminResource;
use Illuminate\Http\Request;

class PosPointsTransactionsResource extends BaseAdminResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "created_at" => $this->created_at,
            "transaction_type" => $this->transaction_type,
            "points" => $this->balance_before - $this->balance_after,
            "transaction_id" => $this?->transaction_id ?? null
        ];
    }
}
