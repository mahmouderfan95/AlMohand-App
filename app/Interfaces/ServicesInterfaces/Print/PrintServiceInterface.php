<?php

namespace App\Interfaces\ServicesInterfaces\Print;

use App\DTO\BaseDTO;
use App\DTO\Pos\Print\DecreaseCountDto;
use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface PrintServiceInterface
{
    public function decreaseCount(DecreaseCountDto $data): mixed;

}
