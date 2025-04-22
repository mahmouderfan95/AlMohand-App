<?php

namespace App\Http\Resources\Admin\Distributor;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\ZoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributorPosTerminalListResource extends BaseAdminResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "pos_terminal_id" => $this->posTerminal->id,
            "name" => $this->posTerminal->name,
            "otp" => $this->otp,
            "distributor" => new DistributorResource($this->distributor),
            "balance" => $this->balance ?? 0.00,
            "commission_balance" => $this->commission ?? 0.00,
            "points_balance" => $this->points ?? 0.00,
            "serial_number" => $this->serial_number,
            "is_active" => $this->is_active ?? false,
            "activated_at" => $this->activated_at ?? null,
            "created_at" => $this->created_at ?? null,
            "updated_at" => $this->updated_at ?? null,
        ];
    }
}
