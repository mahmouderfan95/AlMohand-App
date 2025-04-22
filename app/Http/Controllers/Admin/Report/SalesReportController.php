<?php

namespace App\Http\Controllers\Admin\Report;

use App\DTO\Admin\PosTerminal\CreatePosTerminalDto;
use App\DTO\Admin\Report\SalesReportDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PosTerminalRequests\PosTerminalRequest;
use App\Http\Requests\Admin\Report\SalesReportRequest;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalServiceInterface;
use App\Interfaces\ServicesInterfaces\Report\SalesReportServiceInterface;
use OpenApi\Annotations as OA;

class SalesReportController extends Controller
{
    /**
     * Attribute  Constructor.
     */
    public function __construct(private readonly SalesReportServiceInterface $salesReportService)
    {
    }

    /**
     * @OA\Get(
     *     path="/report/sales/daily",
     *     operationId="getDailySalesReport",
     *     tags={"Sales Report"},
     *     summary="Retrieve daily sales report",
     *     description="Fetches the daily sales report with optional filtering parameters.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2025-02-03")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2025-02-07")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search by relevant keyword.",
     *         @OA\Schema(type="string", example="dasdasdasd")
     *     ),
     *     @OA\Parameter(
     *         name="merchant_id",
     *         in="query",
     *         required=false,
     *         description="Filter by merchant ID.",
     *         @OA\Schema(type="string", format="uuid", example="3a8f7193-7ded-4d8d-8cf0-73089716f8ea")
     *     ),
     *     @OA\Parameter(
     *         name="print_status",
     *         in="query",
     *         required=false,
     *         description="Filter by print status (0 = Not Printed, 1 = Printed).",
     *         @OA\Schema(type="integer", example=0, enum={0,1})
     *     ),
     *     @OA\Parameter(
     *         name="payment_method",
     *         in="query",
     *         required=false,
     *         description="Filter by payment method.",
     *         @OA\Schema(type="string", example="mada")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getDailyReport(SalesReportRequest $request)
    {
        return $this->salesReportService->getDailySalesReport(new SalesReportDto($request));
    }

    /**
     * @OA\Get(
     *     path="/report/sales/total-profit",
     *     operationId="getTotalProfitReport",
     *     tags={"Sales Report"},
     *     summary="Retrieve total profit report",
     *     description="Fetches the total profit report with optional filtering parameters.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search by relevant keyword.",
     *         @OA\Schema(type="string", example="POS-123")
     *     ),
     *     @OA\Parameter(
     *         name="merchant_id",
     *         in="query",
     *         required=false,
     *         description="Filter by merchant ID.",
     *         @OA\Schema(type="string", format="uuid", example="5c6e86bd-81b6-450f-afae-1aa3f50729e0")
     *     ),
     *     @OA\Parameter(
     *         name="print_status",
     *         in="query",
     *         required=false,
     *         description="Filter by print status (0 = Not Printed, 1 = Printed).",
     *         @OA\Schema(type="integer", example=0, enum={0,1})
     *     ),
     *     @OA\Parameter(
     *         name="payment_method",
     *         in="query",
     *         required=false,
     *         description="Filter by payment method.",
     *         @OA\Schema(type="string", example="mada")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getTotalProfitReport(SalesReportRequest $request)
    {
        return $this->salesReportService->getProfitReport(new SalesReportDto($request));
    }

    public function getDetailedProfitReport(SalesReportRequest $request)
    {
        return $this->salesReportService->getProfitDetailsReport(new SalesReportDto($request));
    }
}
