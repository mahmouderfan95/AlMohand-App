<?php

namespace App\Http\Resources\Admin\Report\SalesReport;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Pos\ProductResources\ProductOrderedResource;

class TotalProfitReportResource extends BaseAdminResource
{
    public function toArray($request)
    {
        $product = $this->when($this->orderProduct->product, new ProductOrderedResource($this->orderProduct->product));
        $order_product = $this->when($this->orderProduct, $this->orderProduct);
        return [
            "id" => $this->id,
            'order_id' => $this->order_id,
            'total_cost' => $this->total_cost,
            'total_price' => $this->total_price,
            'total_profit' => $this->total_profit,
            'payment_method' => $this->orderProduct?->order?->payment_method,
            'product' => [
                'id' => $product?->id,
                'name' => $product?->name,
                'brand' => $product?->brand?->name,
                'cost_price' => $order_product?->cost_price,
                'wholesale_price' => $order_product?->wholesale_price,
                'price' => $order_product?->price,
                'quantity' => $order_product?->quantity,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
