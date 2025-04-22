<?php

namespace App\DTO\Pos\Order;

use App\DTO\BaseDTO;
use App\Http\Requests\Pos\Order\AllOrderRequest;
use App\Http\Requests\Pos\Order\StoreOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

/**
 *
 */
class AllOrdersDto extends BaseDTO
{
    /**
     * @var string|null
     */
    protected ?string $date_from = null;

    /**
     * @var string|null
     */
    protected ?string $date_to = null;

    /**
     * @var string|null
     */
    protected ?string $search = null;


    /**
     * @param AllOrderRequest $request
     */
    public function __construct(AllOrderRequest $request)
    {
        parent::__construct($request);
    }


    /**
     * @return string|null
     */
    public function getDateFrom(): ?string
    {
        return $this->date_from;
    }

    public function setDateFrom(?string $dateTimeFrom): void
    {
        $this->date_from = $dateTimeFrom;
    }

    /**
     * @return string|null
     */
    public function getDateTo(): ?string
    {
        return $this->date_from;
    }

    public function setDateTo(?string $dateTimeTo): void
    {
        $this->date_to = $dateTimeTo;
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->date_from;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }


}
