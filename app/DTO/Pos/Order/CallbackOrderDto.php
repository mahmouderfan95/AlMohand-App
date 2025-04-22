<?php

namespace App\DTO\Pos\Order;

use App\DTO\BaseDTO;
use App\Http\Requests\Pos\Order\CallbackOrderRequest;
use App\Http\Requests\Pos\Order\StoreOrderRequest;
use Illuminate\Http\Request;

/**
 *
 */
class CallbackOrderDto extends BaseDTO
{
    /**
     * @var int
     */
    protected int $order_id;
    /**
     * @var array
     */
    protected array $mada_response;


    /**
     * @param Request $request
     */
    public function __construct(CallbackOrderRequest $request)
    {
        parent::__construct($request);
    }


    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function setOrderId(int $orderId): void
    {
        $this->order_id = $orderId;
    }


    /**
     * @return string
     */
    public function getMadaResponse(): array
    {
        return $this->mada_response;
    }

    public function setMadaResponse(array $mada_response): void
    {
        $this->mada_response = $mada_response;
    }
}
