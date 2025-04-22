<?php

namespace App\DTO\Pos\Print;

use App\DTO\BaseDTO;
use App\Http\Requests\Pos\Print\DecreaseCountRequest;
use Illuminate\Http\Request;

/**
 *
 */
class DecreaseCountDto extends BaseDTO
{
    /**
     * @var array
     */
    protected array $order_product_serial_ids;

    /**
     * @var integer
     */
    protected int $all_serials;

    /**
     * @param Request $request
     */
    public function __construct(DecreaseCountRequest $request)
    {
        parent::__construct($request);
    }


    /**
     * @return array
     */
    public function getOrderProductSerialIds(): array
    {
        return $this->order_product_serial_ids;
    }

    public function setOrderProductSerialIds(array $orderProductSerialIds): void
    {
        $this->order_product_serial_ids = $orderProductSerialIds;
    }

    /**
     * @return integer
     */
    public function getAllSerials(): int
    {
        return $this->all_serials;
    }

    public function setAllSerials(int $allSerials): void
    {
        $this->all_serials = $allSerials;
    }
}
