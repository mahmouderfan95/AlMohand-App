<?php

namespace App\Interfaces\ServicesInterfaces\SalesRep;

use App\DTO\BaseDTO;
use App\DTO\Pos\Auth\PosLoginDto;
use App\DTO\Pos\Auth\PosRegisterDto;
use Illuminate\Http\Request;

interface SalesRepAuthServiceInterface
{
    /**
     * @param BaseDto $dto
     * @return mixed
     */
    public function login(BaseDto $data): mixed;

    /**
     * @param BaseDto $dto
     * @return mixed
     */
    public function register(BaseDto $data): mixed;

    public function logout();
}
