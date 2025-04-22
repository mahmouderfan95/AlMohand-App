<?php

namespace App\DTO\Pos\Auth;

use App\DTO\BaseDTO;
use App\Http\Requests\Pos\Auth\FactoryResetRequest;
use App\Http\Requests\Pos\BalanceRequest\RequestBalanceRequest;

class FactoryResetDto extends BaseDTO
{
    /**
     * @var string
     */
    protected string $otp;

    /**
     * @param RequestBalanceRequest $request
     */
    public function __construct(FactoryResetRequest $request)
    {
        parent::__construct($request);
    }

    public function getOtp(): string
    {
        return $this->otp;
    }

    public function setOtp(string $otp): void
    {
        $this->otp = $otp;
    }

}
