<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "name" => $this->name,
            "receipt_content" => $this?->receipt_content,
            "image" => $this->image,
            "price" => $this->price,
            "wholesale_price" => $this->wholesale_price,
            "tax_rate" => 0,
            "profit_rate" => $this->profit_rate,
            'brand' => $this->whenLoaded('brand') ? BrandResource::make($this->brand) : '', // only load when brand is eager loaded
            'category' => $this->whenLoaded('productCategory') ? ProductCategoryResource::make($this->productCategory) : '',
        ];
    }
}
