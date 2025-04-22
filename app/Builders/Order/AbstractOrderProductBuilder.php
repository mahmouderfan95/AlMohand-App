<?php

namespace App\Builders\Order;


use App\Models\Distributor\DistributorPosTerminal;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;

abstract class AbstractOrderProductBuilder
{
    abstract public function set(Order $order, Product $product, int $quantity): self;
    abstract public function reset(OrderProduct $orderProduct): self;
    abstract public function getOrderProduct(): OrderProduct;
    abstract public function selectOrderWithType(): self;
    abstract public function storeFailedOrderReasons(): self;
    abstract public function updateOrderProductPrices(): self;
}
