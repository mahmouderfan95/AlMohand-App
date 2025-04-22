<?php

namespace App\Interfaces\ServicesInterfaces\Order;

use App\DTO\BaseDTO;
use App\DTO\Pos\Order\AllOrdersDto;
use App\DTO\Pos\Order\CallbackOrderDto;
use App\DTO\Pos\Order\StoreOrderDto;
use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface OrderServiceInterface extends BaseServiceInterface
{
    public function getPosOrders(AllOrdersDto $data, $distributor_pos_terminal_id = null): mixed;

    public function store(StoreOrderDto|BaseDTO $data): mixed;

    public function callback(CallbackOrderDto $data): mixed;

    public function getSalesRepPosOrders(AllOrdersDto $data, $distributor_pos_terminal_id);
}
