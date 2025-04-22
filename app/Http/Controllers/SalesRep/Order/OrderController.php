<?php

namespace App\Http\Controllers\SalesRep\Order;

use App\DTO\Pos\Order\AllOrdersDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Order\AllOrderRequest;
use App\Interfaces\ServicesInterfaces\Order\OrderServiceInterface;
use App\Interfaces\ServicesInterfaces\SalesRepUser\SalesRepUserServiceInterface;
use App\Services\BalanceRequest\BalanceRequestService;
use OpenApi\Annotations as OA;

class OrderController extends Controller
{
    /**
     * SalesRepUser  Constructor.
     */
    public function __construct(private readonly OrderServiceInterface $orderService)
    {
    }

    /**
     * @OA\Get(
     *     path="/pos-orders/{pos_terminal_id}",
     *     operationId="getPosOrders",
     *     tags={"POS Orders"},
     *     summary="Retrieve POS orders",
     *     description="Fetches the orders associated with a specific POS terminal, with optional filtering parameters.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="pos_terminal_id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal.",
     *         @OA\Schema(type="string", format="uuid", example="e1045a5b-870e-49d7-81c5-dea3043f76b3")
     *     ),
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
    public function getPosOrders(AllOrderRequest $request, $distributor_pos_terminal_id)
    {
        return $this->orderService->getSalesRepPosOrders(new AllOrdersDto($request), $distributor_pos_terminal_id);
    }

    /**
     * @OA\Get(
     *     path="/order/{id}",
     *     operationId="getOrderDetails",
     *     tags={"POS Orders"},
     *     summary="Retrieve order details",
     *     description="Fetches the details of a specific order using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the order (integer).",
     *         @OA\Schema(type="integer", example=99)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved order details."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->orderService->show($id);
    }

}
