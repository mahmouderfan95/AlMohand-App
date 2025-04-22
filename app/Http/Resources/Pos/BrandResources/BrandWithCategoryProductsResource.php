<?php

namespace App\Http\Resources\Pos\BrandResources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandWithCategoryProductsResource extends JsonResource
{
    /**
     * Category data into an array.
     *
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'description' => $this->description,
            'has_subs' => $this->has_subs,
            'image' => optional($this->images->firstWhere('key', 'logo'))->image, // Extract only the image URL
            'name' => $this->name,
            'categories' => $this->categories,
        ];
    }
}
