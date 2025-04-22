<?php

namespace App\Http\Resources\Admin\Order;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\BrandResource;
use Illuminate\Http\Request;


class OrderProductsResource extends BaseAdminResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->product?->image,
            'name' => $this->product?->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'brand' => new BrandResource($this->brand)
        ];
    }
}
