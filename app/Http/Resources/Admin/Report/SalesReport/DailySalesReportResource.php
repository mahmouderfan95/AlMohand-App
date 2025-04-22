<?php

namespace App\Http\Resources\Admin\Report\SalesReport;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Pos\ProductResources\ProductOrderedResource;

class DailySalesReportResource extends BaseAdminResource
{
    public function toArray($request)
    {
        $product = $this->when($this->orderProduct->product, new ProductOrderedResource($this->orderProduct->product));
        $pos = $this->when($this->orderProduct->order, new DistributorPosTerminalListResource($this->orderProduct->order->owner));
        return [
            "id" => $this->id,
            'order_id' => $this->order_id,
            'price' => $this->orderProduct?->price,
            'payment_method' => $this->orderProduct?->order?->payment_method,
            'print_count' => $this->print_count,
            'serial' => $this->serial,
            'product' => [
                'id' => $product?->id,
                'name' => $product?->name,
                'brand' => $product?->brand?->name,
            ],
            'merchant' => [
                'id' => $pos?->distributor->id,
                'name' => $pos?->distributor->name
            ],
            'pos_terminal' => [
                'id' => $pos->id,
                'name' => $pos->posTerminal->name,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
