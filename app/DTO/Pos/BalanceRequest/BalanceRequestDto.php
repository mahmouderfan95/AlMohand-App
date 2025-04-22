<?php

namespace App\DTO\Pos\BalanceRequest;

use App\DTO\BaseDTO;
use App\Http\Requests\Pos\BalanceRequest\RequestBalanceRequest;

class BalanceRequestDto extends BaseDTO
{
    protected float $amount;
    /**
     * @var bool
     */
    protected ?bool $is_mada = null;

    public function __construct(RequestBalanceRequest $request)
    {
        parent::__construct($request);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getIsMada(): ?bool
    {
        return $this->is_mada;
    }

    public function setIsMada(?bool $is_mada): void
    {
        $this->is_mada = $is_mada;
    }

}
