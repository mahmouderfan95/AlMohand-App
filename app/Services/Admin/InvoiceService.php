<?php

namespace App\Services\Admin;

use App\Repositories\Invoice\InvoiceRepository;
use App\Traits\ApiResponseAble;

class InvoiceService
{

    use ApiResponseAble;


    public function __construct(
        private InvoiceRepository $invoiceRepository,
    )
    {}



}
