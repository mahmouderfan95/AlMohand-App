<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->product?->id,
            "name" => $this->product?->name,
            "image" => $this->product?->image,
            "price" => $this->unit_price,
            "quantity" => $this->quantity,
            "orderProductSerials" => $this->orderProductSerials,
        ];
    }
}
