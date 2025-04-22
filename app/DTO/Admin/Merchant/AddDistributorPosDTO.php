<?php

namespace App\DTO\Admin\Merchant;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;

/**
 * Class AddDistributorPosDTO
 */
class AddDistributorPosDTO extends BaseDTO
{
    /**
     * @var string
     */
    protected string $merchant_id;

    /**
     * @var string
     */
    protected string $pos_terminal_id;

    /**
     * @var string|null
     */
    protected ?string $branch_name = null;

    /**
     * @var string|null
     */
    protected ?string $address = null;

    /**
     * @var string|null
     */
    protected ?string $receiver_phone = null;

    /**
     * @var string|null
     */
    protected ?string $receiver_name = null;

    /**
     * AddDistributorPosDTO constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    /**
     * @param string $merchant_id
     */
    public function setMerchantId(string $merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * @return string
     */
    public function getPosTerminalId(): string
    {
        return $this->pos_terminal_id;
    }

    /**
     * @param string $pos_terminal_id
     */
    public function setPosTerminalId(string $pos_terminal_id): void
    {
        $this->pos_terminal_id = $pos_terminal_id;
    }

    /**
     * @return string|null
     */
    public function getBranchName(): ?string
    {
        return $this->branch_name;
    }

    /**
     * @param string|null $branch_name
     */
    public function setBranchName(?string $branch_name): void
    {
        $this->branch_name = $branch_name;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getReceiverPhone(): ?string
    {
        return $this->receiver_phone;
    }

    /**
     * @param string|null $receiver_phone
     */
    public function setReceiverPhone(?string $receiver_phone): void
    {
        $this->receiver_phone = $receiver_phone;
    }

    /**
     * @return string|null
     */
    public function getReceiverName(): ?string
    {
        return $this->receiver_name;
    }

    /**
     * @param string|null $receiver_name
     */
    public function setReceiverName(?string $receiver_name): void
    {
        $this->receiver_name = $receiver_name;
    }
}
