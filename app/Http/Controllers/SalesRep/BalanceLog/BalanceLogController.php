<?php

namespace App\Http\Controllers\SalesRep\BalanceLog;

use App\Http\Controllers\Controller;
use App\Interfaces\ServicesInterfaces\BalanceLog\BalanceLogServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorPosTerminalServiceInterface;
use OpenApi\Annotations as OA;

class BalanceLogController extends Controller
{
    public function __construct(private readonly BalanceLogServiceInterface $balanceLogService, private readonly DistributorPosTerminalServiceInterface $distributorPosTerminalService)
    {

    }

    /**
     * @OA\Get(
     *     path="/balance-log/points/{id}",
     *     operationId="getBalanceLogPoints",
     *     tags={"Balance Log"},
     *     summary="Retrieve balance log for points",
     *     description="Fetches the balance log for points associated with a specific user or entity.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID associated with the balance log.",
     *         @OA\Schema(type="string", format="uuid", example="a123b456-c789-d012-e345-6789f0123456")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2025-02-01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2025-02-10")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved balance log for points."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getPointsTransactions($distributor_pos_terminal_id)
    {
        return $this->balanceLogService->getPointsTransactionsByDistributorPosTerminal($distributor_pos_terminal_id);
    }

    /**
     * @OA\Get(
     *     path="/balance-log/commission/{id}",
     *     operationId="getBalanceLogCommission",
     *     tags={"Balance Log"},
     *     summary="Retrieve balance log for commission",
     *     description="Fetches the balance log for commission associated with a specific user or entity.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID associated with the balance log.",
     *         @OA\Schema(type="string", format="uuid", example="a123b456-c789-d012-e345-6789f0123456")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         required=false,
     *         description="Filter by start date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2025-02-01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         required=false,
     *         description="Filter by end date (YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2025-02-10")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved balance log for commission."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getCommissionTransactions($distributor_pos_terminal_id)
    {
        return $this->balanceLogService->getCommissionTransactionsByDistributorPosTerminal($distributor_pos_terminal_id);
    }

}
