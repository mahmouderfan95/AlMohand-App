<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Admin\Distributor\DistributorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesRepUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'balance' => $this->balance,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'code' => $this->code,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'transactions' => $this->transactions,
            'parent' => $this->whenLoaded('parent', fn() => new SalesRepUserResource($this->parent)),
            'roles' =>$this->whenLoaded('roles'),
            'children' => SalesRepUserResource::collection($this->whenLoaded('children')),
            'distributors' => DistributorResource::collection($this->whenLoaded('distributors')),
            'sales_rep_level' => $this->whenLoaded('sales_rep_level', fn() => new SalesRepLevelResource($this->sales_rep_level)),
            'sales_rep_locations' => SalesRepLocationResource::collection($this->whenLoaded('sales_rep_locations')),
            'distributors_count' => $this->whenCounted('distributors', $this->distributors_count),
            'distributor_pos_terminals_count' => $this->whenLoaded('distributors', function () {
                return $this->distributors->sum('pos_terminals_count');
            }),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
