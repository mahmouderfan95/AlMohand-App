<?php

namespace App\Repositories\Order;

use App\Enums\Order\OrderStatus;
use App\Models\Order\OrderHistory;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderHistoryRepository extends BaseRepository
{

    public function __construct(
        Application $app,
    ){
        parent::__construct($app);
    }

    public function store($orderId)
    {
        return $this->model->create([
            'order_id' => $orderId,
            'status' => OrderStatus::PENDING,
            'note' => 'first',
        ]);

    }

    /**
     * OrderHistory Model
     *
     * @return string
     */
    public function model(): string
    {
        return OrderHistory::class;
    }
}
