<?php

namespace App\Interfaces\ServicesInterfaces\Merchant;

use App\DTO\Pos\PosLoginDto;
use App\Interfaces\ServicesInterfaces\BaseServiceInterface;

interface HomeServiceInterface extends BaseServiceInterface
{
    public function home();

}
