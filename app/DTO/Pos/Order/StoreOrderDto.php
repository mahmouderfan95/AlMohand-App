<?php

namespace App\DTO\Pos\Order;

use App\DTO\BaseDTO;
use App\Http\Requests\Pos\Order\StoreOrderRequest;
use Illuminate\Http\Request;

/**
 *
 */
class StoreOrderDto extends BaseDTO
{
    /**
     * @var int
     */
    protected int $product_id;
    /**
     * @var int
     */
    protected int $quantity;
    /**
     * @var string
     */
    protected string $payment_method;


    /**
     * @param Request $request
     */
    public function __construct(StoreOrderRequest $request)
    {
        parent::__construct($request);
    }


    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $productId): void
    {
        $this->product_id = $productId;
    }


    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->product_id;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->payment_method = $paymentMethod;
    }
}
