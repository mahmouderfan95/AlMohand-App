<?php

namespace App\Http\Controllers\SalesRep\SubSalesRep;

use App\DTO\Admin\BalanceRequest\BalanceRequestStatusDto;
use App\DTO\BaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BalanceRequest\UpdateBalanceRequest;
use App\Http\Requests\Admin\SalesRepUserRequests\SalesRepUserStatusRequest;
use App\Http\Requests\SalesRep\Transaction\AddTransactionRequest;
use App\Interfaces\ServicesInterfaces\SalesRepUser\SalesRepUserServiceInterface;
use App\Services\BalanceRequest\BalanceRequestService;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SalesRepUserRequests\SalesRepUserRequest;

class SubSalesRepController extends Controller
{
    public $salesRepUserService;
    public $balanceRequestService;

    /**
     * SalesRepUser  Constructor.
     */
    public function __construct(SalesRepUserServiceInterface $salesRepUserService , BalanceRequestService $balanceRequestService)
    {
        $this->salesRepUserService = $salesRepUserService;
        $this->balanceRequestService = $balanceRequestService;
    }


    /**
     * @OA\Get(
     *     path="/sub-sales/{id}",
     *     operationId="getSalesRepUserById",
     *     tags={"SalesRepUser"},
     *     summary="Retrieve a single salesRepUser",
     *     description="Fetches details of a specific salesRepUser by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID or disrubuter id or pos terminal id ",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             ref="#/components/responses/Unauthenticated"
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        return $this->salesRepUserService->show($id);
    }


    /**
     * @OA\Get(
     *     path="/transactions",
     *     operationId="getSalesRepUserTransactions",
     *     tags={"SalesRepUser"},
     *     summary="Retrieve salesRepUser transactions",
     *     description="Fetches transactions based on type and date range.",
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="Filter by transaction type (sales_rep, pos, distributor)",
     *         @OA\Schema(type="string", enum={"sales_rep", "pos", "distributor"})
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         description="Filter transactions from this date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=false,
     *         description="Filter transactions up to this date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/responses/Unauthenticated")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function transactions(Request $request)
    {
        return $this->salesRepUserService->transactions($request);
    }


    /**
     * @OA\Get(
     *     path="/all-balance-requests",
     *     operationId="getSalesRepUserAllBalanceRequests",
     *     tags={"BalanceRequest"},
     *     summary="Retrieve salesRepUser allBalanceRequests",
     *     description="Fetches all balance requests with optional filters",
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Filter balance requests created from this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="Filter balance requests created up to this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status (accepted, rejected)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"accepted", "rejected"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/responses/Unauthenticated")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function allBalanceRequests(Request $request)
    {
        return $this->salesRepUserService->allBalanceRequests($request);
    }


    /**
     * @OA\Get(
     *     path="/pending-balance-request",
     *     operationId="getSalesRepUserPendingBalanceRequests",
     *     tags={"BalanceRequest"},
     *     summary="Retrieve salesRepUser allBalanceRequests",
     *     description="Fetches allBalanceRequests",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/responses/Unauthenticated")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function pendingBalanceRequests(Request $request)
    {
        return $this->salesRepUserService->pendingBalanceRequests($request);
    }



    /**
     * @OA\Get(
     *     path="/sub-sales/{id}/merchants",
     *     operationId="getMerchantsSalesRepUserById",
     *     tags={"SalesRepUser"},
     *     summary="Retrieve a single salesRepUser",
     *     description="Fetches details of a specific salesRepUser by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             ref="#/components/responses/Unauthenticated"
     *         )
     *     )
     * )
     */
    public function merchants($id)
    {
        return $this->salesRepUserService->merchants($id);
    }


    /**
     * @OA\Get(
     *     path="/sub-sales/pos-terminal/{merchant_id}",
     *     operationId="getPosTerminalSalesRepUserById",
     *     tags={"pos-terminal"},
     *     summary="Retrieve pos-terminal by merchant id",
     *     description="Fetches detailspos-terminal by merchant id.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="merchant  ID",
     *         example="039c5d41-5b79-4fcb-baa8-f854737c217d",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             ref="#/components/responses/Unauthenticated"
     *         )
     *     )
     * )
     */
    public function posTerminalByMerchantID($distributor_id)
    {
        return $this->salesRepUserService->posTerminalByMerchantID($distributor_id);
    }


    /**
     * @OA\Get(
     *     path="/sub-sales/merchants-with-pos-terminal/getAll",
     *     operationId="allMerchantsWithPosTerminalByMerchantID",
     *     tags={"merchants-with-pos-terminal"},
     *     summary="Retrieve pos-terminal by merchant id",
     *     description="Fetches detailspos-terminal by merchant id.",
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             ref="#/components/responses/Unauthenticated"
     *         )
     *     )
     * )
     */
    public function allMerchantsWithPosTerminalByMerchantID()
    {
        return $this->salesRepUserService->allMerchantsWithPosTerminalByMerchantID();
    }


    /**
     * @OA\Post(
     *     path="/salesRepUser/add-transaction/{id}",
     *     operationId="addTransactionSalesRepUser",
     *     tags={"SalesRepUser"},
     *     summary="Add a transaction for a Sales Rep User",
     *     description="Adds a transaction (credit or debit) for a specific Sales Rep User by ID.",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Transaction details",
     *         @OA\JsonContent(
     *             required={"type", "transaction_type", "amount", "balance_type"},
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 enum={"sales_rep", "pos", "merchant"},
     *                 example="sales_rep"
     *             ),
     *             @OA\Property(
     *                 property="transaction_type",
     *                 type="string",
     *                 enum={"credit", "debit"},
     *                 example="credit"
     *             ),
     *             @OA\Property(
     *                 property="amount",
     *                 type="integer",
     *                 minimum=1,
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="balance_type",
     *                 type="string",
     *                 example="wallet"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Transaction added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction added successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function addTransaction(AddTransactionRequest $request,  $id)
    {
        return $this->salesRepUserService->addTransaction($request, $id);
    }


    /**
     * @OA\Post(
     *     path="/balance-request/{id}/update",
     *     operationId="updateBalanceRequest",
     *     tags={"BalanceRequest"},
     *     summary="Update a Balance Request",
     *     description="Updates the status of a balance request (Accept or Reject).",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Balance Request ID",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Balance request update details",
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"accepted", "rejected"},
     *                 example="accepted"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Transaction added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction added successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function UpdateBalanceRequest(UpdateBalanceRequest $request,  $id)
    {
        return $this->balanceRequestService->update($id, new BalanceRequestStatusDto($request));
    }

}
