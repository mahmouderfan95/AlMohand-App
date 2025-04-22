<?php

namespace App\Http\Resources\Admin\Order;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\Distributor\DistributorDetailsResource;
use Illuminate\Http\Request;


class OrdersResource extends BaseAdminResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'total' => $this->total,
            'order_source' => $this->order_source,
            'currency' => $this->currency,
            'merchant' => $this->owner->distributor->name,
            'pos_name' => $this->owner?->posTerminal?->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
