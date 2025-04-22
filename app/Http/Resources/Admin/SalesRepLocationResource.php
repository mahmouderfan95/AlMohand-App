<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesRepLocationResource extends JsonResource
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
            'sales_rep_id' => $this->sales_rep_id,
            'city_id' => $this->city_id,
            'region_id' => $this->region_id,
            'city' => new CityResource($this->city),
            'region' => new RegionResource($this->region),
        ];
    }
}
