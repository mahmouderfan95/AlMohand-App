<?php

namespace App\Interfaces\ServicesInterfaces\PosTerminal;

use App\DTO\Pos\Auth\PosLoginDto;
use App\DTO\Pos\Auth\PosRegisterDto;

/**
 *
 */
interface PosAuthServiceInterface
{
    /**
     * @param PosLoginDto $dto
     * @return mixed
     */
    public function login(PosLoginDto $dto): mixed;

    /**
     * @param PosRegisterDto $dto
     * @return mixed
     */
    public function register(PosRegisterDto $dto): mixed;

    /**
     * @param $name
     * @return mixed
     */
    public function updateName($name);

    /**
     * @param $phone
     * @return mixed
     */
    public function updatePhone($phone);

    /**
     * @param $old_password
     * @param $new_password
     * @return mixed
     */
    public function updatePassword($old_password, $new_password);

    /**
     * @return mixed
     */
    public function logout();
}
