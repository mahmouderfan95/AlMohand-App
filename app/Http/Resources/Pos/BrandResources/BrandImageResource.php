<?php

namespace App\Http\Resources\Pos\BrandResources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandImageResource extends JsonResource
{
    /**
     * Category data into an array.
     *
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            'key' => $this->key,
            'image' => $this->image,
        ];
    }
}
