<?php

namespace App\Http\Resources\SalesRep\Distributor;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Admin\Order\OrderProductsResource;
use Illuminate\Http\Request;

class DistributorPosTerminalResource extends BaseAdminResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "pos_terminal_id" => $this->posTerminal->id,
            "name" => $this->posTerminal->name,
            "branch_name" => $this->branch_name ?? "",
            "address" => $this->address ?? "",
            "admin_name" => $this->receiver_name ?? "",
            "admin_phone" => $this->receiver_phone ?? "",
            "merchant" => [
                'id' => $this->distributor_id,
                'name' => $this->distributor->name
            ],
            "balance" => $this->balance ?? 0.00,
            "commission" => $this->commission ?? 0.00,
            "points" => (string)$this->points ?? 0.00,
            "is_active" => (boolean) $this->is_active ?? false,
            "activated_at" => $this->activated_at ?? null,
            "created_at" => $this->created_at ?? null,
            "updated_at" => $this->updated_at ?? null,
        ];
    }
}
