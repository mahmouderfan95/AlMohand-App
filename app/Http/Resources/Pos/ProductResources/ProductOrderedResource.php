<?php

namespace App\Http\Resources\Pos\ProductResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOrderedResource extends JsonResource
{
    /**
     * Category data into an array.
     *
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image,
            "price" => $this->price,
            // "wholesale_price" => $this->wholesale_price,
            "policy" => $this->name,    // change this for policy column for how to use this card later
        ];
    }
}
