<?php

namespace App\Repositories\Order;

use App\Models\Customer;
use App\Models\Order\OrderUser;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderUserRepository extends BaseRepository
{
    public function __construct(
        Application                          $app,
    )
    {
        parent::__construct($app);
    }


    public function showByOrderId($orderId)
    {
        return $this->model->where('order_id', $orderId)->first();
    }

    public function checkByOrderIdAndUserId($orderId, $userId)
    {
        return $this->model
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }

    public function store($orderId, $userId)
    {
        return $this->model->create([
            'order_id' => $orderId,
            'user_id' => $userId,
        ]);
    }


    public function model(): string
    {
        return OrderUser::class;
    }
}
