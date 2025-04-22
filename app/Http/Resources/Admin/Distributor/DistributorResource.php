<?php

namespace App\Http\Resources\Admin\Distributor;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalTransactionsResource;
use App\Http\Resources\Admin\SalesRepUserResource;
use App\Http\Resources\Admin\ZoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributorResource extends BaseAdminResource
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
            "transactions" => PosTerminalTransactionsResource::collection($this->transactions),
            "name" => $this->name,
            "distributor_group" => $this->group->name ?? "",
            "logo" => $this->logo,
            "balance" => $this->balance ?? 0.00,
            "sales_rep" => new SalesRepUserResource($this->sales_rep),
            "pos_terminals_count" => $this->posTerminals?->count(),
            "email" => $this->email,
            "manager_name" => $this->manager_name,
            "owner_name" => $this->owner_name,
            "phone" => $this->phone,
            "address" => $this->address,
            "location" => $this->location,
            "city" => $this->city,
            "zone" => $this->zone,
            "region" => $this->region,
            "is_active" => $this->is_active ?? false,
            "created_at" => $this->created_at ?? null,
            "updated_at" => $this->updated_at ?? null,
        ];
    }
}
