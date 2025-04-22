<?php

namespace App\Http\Resources\SalesRep\Order;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorDetailsResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Admin\Order\OrderProductsResource;
use App\Http\Resources\SalesRep\Distributor\DistributorPosTerminalResource;
use App\Models\Distributor\DistributorPosTerminal;
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
            'vat' => $this->vat ?? 0,
            'tax' => $this->tax ?? 0,
            'discount' => $this->discount ?? 0,
            'total' => $this->total,
            'order_source' => $this->order_source,
            'currency' => $this->currency ?? 'ر.س',
            'order_products' => OrderProductsResource::collection($this->order_products),
            'pos_terminal' => new DistributorPosTerminalResource($this->owner),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
