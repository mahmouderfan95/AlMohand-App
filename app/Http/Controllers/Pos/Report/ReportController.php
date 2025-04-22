<?php

namespace App\Http\Controllers\Pos\Report;

use App\Http\Controllers\Controller;
use App\Interfaces\ServicesInterfaces\Report\ReportServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ReportController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="Report",
     *     type="object",
     *     title="Report",
     *     description="Report model",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Report Name"),
     *     @OA\Property(property="category_id", type="integer", example=123),
     *     @OA\Property(property="logo", type="string", example="https://example.com/logo.png")
     * )
     */
    public function __construct(private ReportServiceInterface $reportService)
    {
    }

    /**
     * @OA\Get(
     *     path="/reports/order-reports",
     *     operationId="orderReports",
     *     tags={"Report"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Filter for the order reports. Options are: 'today', 'tomorrow', 'specific_date_range'",
     *          required=false,
     *          @OA\Schema(type="string", example="today")
     *      ),
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Start date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-01")
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="End date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-05")
     *      ),
     *     @OA\Parameter(
     *          name="payment_method",
     *          in="query",
     *          description="Filter for the payment method. Options are: 'mada', 'balance', etc.",
     *          required=false,
     *          @OA\Schema(type="string", example="mada")
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reports for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Report")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function orderReports(Request $request)
    {
        return $this->reportService->orderReports($request, 'f351873e-bb95-11ef-8462-0432019d7656');
    }


    /**
     * @OA\Get(
     *     path="/reports/balance-reports",
     *     operationId="balanceReports",
     *     tags={"Report"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Filter for the balanceReports reports. Options are: 'today', 'tomorrow', 'specific_date_range'",
     *          required=false,
     *          @OA\Schema(type="string", example="today")
     *      ),
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Start date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-01")
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="End date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-05")
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reports for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Report")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function balanceReports(Request $request)
    {
        return $this->reportService->balanceReports($request, 'f351873e-bb95-11ef-8462-0432019d7656');
    }


    /**
     * @OA\Get(
     *     path="/reports/commission-reports",
     *     operationId="commissionReports",
     *     tags={"Report"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Filter for the balanceReports reports. Options are: 'today', 'tomorrow', 'specific_date_range'",
     *          required=false,
     *          @OA\Schema(type="string", example="today")
     *      ),
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Start date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-01")
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="End date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-05")
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reports for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Report")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function commissionReports(Request $request)
    {
        return $this->reportService->commissionReports($request, auth('posApi')->user());
    }


    /**
     * @OA\Get(
     *     path="/reports/balance-request-reports",
     *     operationId="balanceRequestReports",
     *     tags={"Report"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Filter for the balanceReports reports. Options are: 'today', 'tomorrow', 'specific_date_range'",
     *          required=false,
     *          @OA\Schema(type="string", example="today")
     *      ),
     *      @OA\Parameter(
     *           name="status",
     *           in="query",
     *           description="Filter by balance request status. Options: 'pending', 'accepted', 'rejected'",
     *           required=false,
     *           @OA\Schema(
     *               type="string",
     *               enum={"pending", "accepted", "rejected"},
     *               example="pending"
     *           )
     *       ),
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Start date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-01")
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="End date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-05")
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reports for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Report")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function balanceRequestReports(Request $request)
    {
        return $this->reportService->balanceRequestReports($request, []);
    }



    /**
     * @OA\Get(
     *     path="/reports/point-reports",
     *     operationId="pointReports",
     *     tags={"Report"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Filter for the balanceReports reports. Options are: 'today', 'tomorrow', 'specific_date_range'",
     *          required=false,
     *          @OA\Schema(type="string", example="today")
     *      ),
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Start date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-01")
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="End date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-05")
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reports for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Report")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function pointReports(Request $request)
    {
        return $this->reportService->pointReports($request, auth('posApi')->user());
    }


    /**
     * @OA\Get(
     *     path="/reports/main-commission-reports",
     *     operationId="maincommissionReports",
     *     tags={"Report"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Filter for the balanceReports reports. Options are: 'today', 'tomorrow', 'specific_date_range'",
     *          required=false,
     *          @OA\Schema(type="string", example="today")
     *      ),
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Start date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-01")
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="End date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-05")
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reports for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Report")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function mainCommissionReports(Request $request)
    {
        return $this->reportService->mainCommissionReports($request, auth('posApi')->user());
    }



    /**
     * @OA\Get(
     *     path="/reports/main-point-reports",
     *     operationId="mainpointReports",
     *     tags={"Report"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Filter for the balanceReports reports. Options are: 'today', 'tomorrow', 'specific_date_range'",
     *          required=false,
     *          @OA\Schema(type="string", example="today")
     *      ),
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Start date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-01")
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="End date for the specific date range filter. Format: YYYY-MM-DD",
     *          required=false,
     *          @OA\Schema(type="string", example="2025-02-05")
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reports for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Report")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function mainPointReports(Request $request)
    {
        return $this->reportService->mainPointReports($request, auth('posApi')->user());
    }


}
