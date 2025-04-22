<?php

namespace App\DTO\Pos\Auth;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;


class PosRegisterDto extends BaseDTO
{
    /**
     * @var string
     */
    protected string $serial_number;
    /**
     * @var string
     */
    protected string $password;
    /**
     * @var string|null
     */
    protected ?string $otp;


    protected ?string $location;

    /**
     * @var string|null
     */
    protected ?string $ip;

    protected ?string $app_version;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->setIp();
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return void
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @return void
     */
    public function setIp(): void
    {
        $this->ip = \request()->ip();
    }

    /**
     * @return string|null
     */
    public function getAppVersion(): ?string
    {
        return $this->app_version;
    }

    /**
     * @param string|null $app_version
     * @return void
     */
    public function setAppVersion(?string $app_version): void
    {
        $this->app_version = $app_version;
    }

    /**
     * @return string
     */
    public function getSerialNumber(): string
    {
        return $this->serial_number;
    }

    /**
     * @param string $serial_number
     * @return void
     */
    public function setSerialNumber(string $serial_number): void
    {
        $this->serial_number = $serial_number;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getOtp(): ?string
    {
        return $this->otp;
    }

    public function setOtp(?string $otp): void
    {
        $this->otp = $otp;
    }


}
