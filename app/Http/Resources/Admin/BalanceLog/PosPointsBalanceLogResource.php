<?php

namespace App\Http\Resources\Admin\BalanceLog;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorGroupConditionResource;
use App\Http\Resources\Admin\Distributor\DistributorResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalResource;
use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;

class PosPointsBalanceLogResource extends BaseAdminResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "created_at" => $this->created_at,
            'order_id' => '#54525',
            "transaction_type" => $this->transaction_type,
            "redeemed_points" => $this->balance_before - $this->balance_after,
            "remaining_points" => $this->balance_after
        ];
    }
}
