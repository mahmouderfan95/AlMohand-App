<?php

namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            "brand_id" => $this->brand_id,
            "parent_id" => $this->parent_id,
            "level" => $this->level,
            "status" => $this->status,
            "web" => $this->web,
            "mobile" => $this->mobile,
            "ancestors" => $this->whenLoaded('ancestors', new self($this->ancestors)),
        ];
    }
}
