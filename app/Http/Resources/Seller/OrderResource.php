<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class OrderResource extends JsonResource
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
            "order_id"=> $this->id,
            "payment_method"=> $this->payment_method,
            "real_amount"=> $this->real_amount,
            "vat"=> $this->vat,
            "tax"=> $this->tax,
            "order_source"=> $this->order_source,
            "created_at" => Carbon::parse($this->created_at)->format("Y-m-d H:i:s"),
            "products_count" => $this->order_products->count() ?? 0,
            "total" => $this->total,
            "sub_total" => $this->sub_total,
            "status" => $this->status,
            'products' => $this->order_products->isNotEmpty() ? [
                'name' => $this->order_products[0]->product->name ?? null,
                'image' => $this->order_products[0]->product->image ?? null,
                'price' => $this->order_products[0]->price,
                'quantity' => $this->order_products[0]->quantity,
                'order_product_id' => $this->order_products[0]->id,
                'product_serial_id' => $this->order_products[0]->orderProductSerials[0]->product_serial_id ?? null,
                'serial' => $this->order_products[0]->orderProductSerials[0]->serial ?? null,
                'scratching' => $this->order_products[0]->orderProductSerials[0]->scratching ?? null,
                'buying' => $this->order_products[0]->orderProductSerials[0]->buying ?? null,
                'expiring' => $this->order_products[0]->orderProductSerials[0]->expiring ?? null,
                'print_count' => $this->order_products[0]->orderProductSerials[0]->print_count ?? null,
                'max_print_count' => $this->order_products[0]->orderProductSerials[0]->max_print_count ?? null,
            ] : null
        ];
    }
}
