<?php

namespace App\Repositories\Order;

use App\Models\Order\OrderProductOptionValue;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderProductOptionValueRepository extends BaseRepository
{
    public function __construct(
        Application $app
    )
    {
        parent::__construct($app);
    }

    public function store($orderProductId, $orderProductOptionId, $optionValueIds)
    {
        $insertArray = [];
        foreach ($optionValueIds as $id) {
            $insertArray[] = [
                'order_product_id' => $orderProductId,
                'order_product_option_id' => $orderProductOptionId,
                'option_value_id' => $id,
            ];
        }

        return $this->model->insert($insertArray);

    }




    public function model(): string
    {
        return OrderProductOptionValue::class;
    }
}
