<?php

namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\TempStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;

class TempStockRepository extends BaseRepository
{


    public function getReservedStock(OrderProduct $orderProduct)
    {
        Log::info($orderProduct->order_id);
        Log::info($orderProduct->product_id);
        return  $this->model
            ->where('order_id', $orderProduct->order_id)
            ->where('product_id', $orderProduct->product_id)
            ->where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->pluck('product_serial_id')
            ->toArray();
    }

    public function model(): string
    {
        return TempStock::class;
    }
}
