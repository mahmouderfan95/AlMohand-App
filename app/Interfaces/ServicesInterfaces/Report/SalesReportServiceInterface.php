<?php

namespace App\Interfaces\ServicesInterfaces\Report;

use App\DTO\Admin\Report\SalesReportDto;

interface SalesReportServiceInterface
{
    public function getDailySalesReport(SalesReportDto $dto);
    public function getProfitReport(SalesReportDto $dto);
    public function getProfitDetailsReport(SalesReportDto $dto);
}
