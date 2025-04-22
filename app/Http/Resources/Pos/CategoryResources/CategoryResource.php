<?php

namespace App\Http\Resources\Pos\CategoryResources;

use App\Http\Resources\Pos\BrandResources\BrandResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Category data into an array.
     *
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "parent_id" => $this->parent_id,
            "brand_id" => $this->brand_id,
            "name" => $this->name,
            "description" => $this->description,
            "image" => $this->image,
            'child_count' => $this->child_count,
            "status" => $this->status,
            'parent' => $this->whenLoaded('parent', new self($this->parent)),
            'brand' => $this->whenLoaded('brand', new BrandResource($this->brand)),
        ];
    }
}
