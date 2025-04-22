<?php

namespace App\Repositories\Order;

use App\Models\Order\OrderPaymentTransaction;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderPaymentTransactionRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function store(array $data)
    {
        return $this->model->create([
            'order_id' => $data['order_id'],
            'full_response' => json_encode( $data['mada_response'] )
        ]);
    }


    public function model(): string
    {
        return OrderPaymentTransaction::class;
    }
}
