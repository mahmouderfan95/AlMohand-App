<?php

namespace App\Http\Resources\Admin\Order;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorDetailsResource;
use Illuminate\Http\Request;


class OrderDetailsResource extends BaseAdminResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'real_amount' => $this->real_amount,
            'sub_total' => $this->sub_total,
            'vat' => $this->vat,
            'tax' => $this->tax,
            'total' => $this->total,
            'order_source' => $this->order_source,
            'currency' => $this->currency,
            'order_products' => OrderProductsResource::collection($this->order_products),
            'merchant' => new DistributorDetailsResource($this->owner->distributor),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
