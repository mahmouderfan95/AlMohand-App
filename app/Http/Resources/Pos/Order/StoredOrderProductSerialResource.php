<?php

namespace App\Http\Resources\Pos\Order;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\ZoneResource;
use App\Http\Resources\Pos\ProductResources\ProductOrderedResource;
use App\Http\Resources\Pos\ProductResources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoredOrderProductSerialResource extends BaseAdminResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'order_id' => $this->order_id,
            'order_product_id' => $this->order_product_id,
            'product_serial_id' => $this->product_serial_id,
            'serial' => $this->serial,
            'scratching' => $this->scratching,
            // 'buying' => $this->buying,
            'expiring' => $this->expiring,
            'print_count' => $this->print_count,
            'max_print_count' => $this->max_print_count,
            'order_product' => $this->when($this->orderProduct, new OrderProductInfoResource($this->orderProduct)),
            'product' => $this->when($this->orderProduct->product, new ProductOrderedResource($this->orderProduct->product)),
            'payment_method' => $this->when($this->orderProduct->order, $this->orderProduct->order->payment_method),
            'total' => $this->when($this->orderProduct->order, $this->orderProduct->order->total),
            'created_at' => $this->created_at,
        ];
    }
}
