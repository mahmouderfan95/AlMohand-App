<?php

namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\Admin\Distributor\DistributorGroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceDistributorGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "product_id" => $this->product_id,
            "merchant_group_id" => $this->distributor_group_id,
            "price" => $this->price,
            "amount_percentage" => $this->amount_percentage,
            "minimum_quantity" => $this->minimum_quantity,
            "max_quantity" => $this->max_quantity,
            "points_buy" => $this->points_buy,
            "points_sell" => $this->points_sell,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "merchant_group" => $this->whenLoaded('distributorGroup', new DistributorGroupResource($this->distributorGroup)),
        ];
    }
}
