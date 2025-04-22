<?php

namespace App\Http\Resources\Pos\ProductResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Category data into an array.
     *
     */
    public function toArray($request)
    {
        $attributes = $this->resource->toArray();
        return [
            "id" => $this->id,
            "name" => $this->name,
            "receipt_content" => $this?->receipt_content,
            "image" => $this->image,
            "quantity" => $this->quantity,
            "status" => $this->status,
            "price" => $this->price,
            "wholesale_price" => $this->wholesale_price,
            "type" => $this->type,
            "tax_id" => $this->tax_id,
            "tax_type" => $this->tax_type,
            "tax_amount" => $this->tax_amount,
            "is_live_integration" => $this->is_live_integration,
            "web" => $this->web,
            "mobile" => $this->mobile,

        ];
    }
}
