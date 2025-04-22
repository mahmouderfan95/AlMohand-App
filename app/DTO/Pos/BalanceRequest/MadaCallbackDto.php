<?php

namespace App\DTO\Pos\BalanceRequest;

use App\DTO\BaseDTO;
use App\Http\Requests\Pos\BalanceRequest\MadaCallbackRequest;
use App\Http\Requests\Pos\BalanceRequest\RequestBalanceRequest;

class MadaCallbackDto extends BaseDTO
{
    /**
     * @var string
     */
    protected string $payment_code;
    /**
     * @var string
     */
    protected string $payment_gateway = "mada";

    /**
     * @var
     */
    protected mixed $payment_gateway_response;

    /**
     * @param MadaCallbackRequest $request
     */
    public function __construct(MadaCallbackRequest $request)
    {
        parent::__construct($request);
    }

    public function getPaymentCode(): string
    {
        return $this->payment_code;
    }

    public function setPaymentCode(string $payment_code): void
    {
        $this->payment_code = $payment_code;
    }

    public function getPaymentGateway(): string
    {
        return $this->payment_gateway;
    }

    public function setPaymentGateway(string $payment_gateway): void
    {
        $this->payment_gateway = $payment_gateway;
    }

    /**
     * @return mixed
     */
    public function getPaymentGatewayResponse(): mixed
    {
        return $this->payment_gateway_response;
    }

    /**
     * @param mixed $payment_gateway_response
     */
    public function setPaymentGatewayResponse($payment_gateway_response): void
    {
        $this->payment_gateway_response = $payment_gateway_response;
    }
}
