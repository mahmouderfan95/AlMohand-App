<?php

namespace App\Interfaces\ServicesInterfaces\BalanceRequest;

use App\DTO\Pos\BalanceRequest\MadaCallbackDto;
use App\Interfaces\ServicesInterfaces\BaseServiceInterface;

interface BalanceRequestServiceInterface extends BaseServiceInterface
{
    public function madaCallback(MadaCallbackDto $dto);
}
