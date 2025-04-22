<?php

namespace App\DTO\Admin\Report;

use App\DTO\BaseDTO;
use App\Http\Requests\Admin\Report\SalesReportRequest;

class SalesReportDto extends BaseDTO
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
     * @var string|null
     */
    protected ?string $merchant_id = null;

    /**
     * @var int|null
     */
    protected ?int $print_status = null;

    /**
     * @var string|null
     */
    protected ?string $payment_method = null;

    public function __construct(SalesReportRequest $request)
    {
        parent::__construct($request);
    }

    public function getDateFrom(): ?string
    {
        return $this->date_from;
    }

    public function setDateFrom(?string $date_from): void
    {
        $this->date_from = $date_from;
    }

    public function getDateTo(): ?string
    {
        return $this->date_to;
    }

    public function setDateTo(?string $date_to): void
    {
        $this->date_to = $date_to;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getMerchantId(): ?string
    {
        return $this->merchant_id;
    }

    public function setMerchantId(?string $merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    public function getPrintStatus(): ?int
    {
        return $this->print_status;
    }

    public function setPrintStatus(?int $print_status): void
    {
        $this->print_status = $print_status;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(?string $payment_method): void
    {
        $this->payment_method = $payment_method;
    }
}
