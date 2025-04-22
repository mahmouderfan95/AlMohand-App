<?php

namespace App\Http\Resources\Pos\Order;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\ZoneResource;
use App\Http\Resources\Pos\ProductResources\ProductOrderedResource;
use App\Http\Resources\Pos\ProductResources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupedOrderResource extends BaseAdminResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->map(function ($orders, $date) {
            return [
                'date' => $date,
                'cards' => StoredOrderProductSerialResource::collection($orders),
            ];
        })->values()->toArray();
    }
}
