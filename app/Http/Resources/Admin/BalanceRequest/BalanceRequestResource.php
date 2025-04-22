<?php

namespace App\Http\Resources\Admin\BalanceRequest;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorGroupConditionResource;
use App\Http\Resources\Admin\Distributor\DistributorResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalResource;
use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;

class BalanceRequestResource extends BaseAdminResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "transaction_id" => $this->transaction_id,
            "pos" => new PosTerminalResource($this->posTerminal),
            "merchant" => new DistributorResource($this->distributor),
            "status" => $this->status,
            "amount" => $this->amount,
            "created_at" => $this->created_at
        ];
    }
}
