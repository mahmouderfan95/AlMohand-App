<?php

namespace App\DTO\Admin\PosTerminal;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;

/**
 *
 */
class CreatePosTerminalDto extends BaseDTO
{
    /**
     * @var string|null
     */
    protected ?string $name = null;
    /**
     * @var string
     */
    protected string $brand;
    /**
     * @var string
     */
    protected string $serial_number;
    /**
     * @var string
     */
    protected string $terminal_id;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     * @return void
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
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

    /**
     * @return string
     */
    public function getTerminalId(): string
    {
        return $this->terminal_id;
    }

    /**
     * @param string $terminal_id
     * @return void
     */
    public function setTerminalId(string $terminal_id): void
    {
        $this->terminal_id = $terminal_id;
    }
}
