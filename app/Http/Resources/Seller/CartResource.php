<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            "seller_id" => $this->owner_id,
            "seller_name" => $this->owner?->name,
            'cartPrice' => $this->cart_price,
            'totalPrice' => $this->total_price,
            'qty' => $this->cartProducts->sum('quantity'),
            'products' => ProductResource::collection($this->products),
        ];
    }
}
