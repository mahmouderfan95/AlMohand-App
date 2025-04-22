<?php

namespace App\Http\Resources\Admin\Distributor;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\ZoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributorPosTerminalDetailsResource extends BaseAdminResource
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
            "branch_name" => $this->branch_name ?? "",
            "address" => $this->address ?? "",
            "admin_name" => $this->receiver_name ?? "",
            "admin_phone" => $this->receiver_phone ?? "",
            "merchant" => new DistributorResource($this->distributor),
            "otp" => $this->otp,
            "balance" => $this->balance ?? 0.00,
            "commission" => $this->commission ?? 0.00,
            "points" => $this->points ?? 0,
            "brand" => $this->posTerminal->brand,
            "is_active" => $this->is_active ?? false,
            "activated_at" => $this->activated_at ?? null,
            "created_at" => $this->created_at ?? null,
            "updated_at" => $this->updated_at ?? null,
        ];
    }
}
