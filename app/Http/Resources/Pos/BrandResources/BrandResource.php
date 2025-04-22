<?php

namespace App\Http\Resources\Pos\BrandResources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            //  'images' => BrandImageResource::collection($this->images),
            'image' =>  $this->image, // Extract only the image URL
            'name' => $this->name,
        ];
    }
}
