<?php

namespace App\Http\Controllers\Admin\Distributor;

use App\DTO\Admin\Merchant\CreateMerchantGroupDto;
use App\DTO\Admin\Merchant\GetDistributorTransactionsDto;
use App\DTO\Admin\Merchant\UpdateBalanceDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Distributor\DistributorGroupRequest;
use App\Http\Requests\Admin\Distributor\UpdateBalanceRequest;
use App\Http\Requests\Admin\PosTerminalRequests\GetDistributorTransactionsRequest;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalTransactionServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class DistributorTransactionsController extends Controller
{

    /**
     * Attribute  Constructor.
     */
    public function __construct(private readonly PosTerminalTransactionServiceInterface $posTerminalTransactionService)
    {
    }

    /**
     * @OA\Post(
     *     path="/merchant/{id}/transactions/balance",
     *     operationId="getMerchantBalanceTransaactions",
     *     tags={"Merchant Transactions"},
     *     summary="Retrieve merchant balance transaction",
     *     description="Fetches the balance of transactions for a specific merchant with optional filters.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the merchant.",
     *         @OA\Schema(type="string", format="uuid", example="5c6e86bd-81b6-450f-afae-1aa3f50729e0")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="filters",
     *                 type="object",
     *                 description="Optional filters to narrow down the transactions.",
     *                 @OA\Property(property="description", type="string", example="ِشحن كارت", description="Transaction description."),
     *                 @OA\Property(property="type", type="string", example="credit", description="Transaction type: credit or debit."),
     *                 @OA\Property(property="amount", type="number", example=1500, description="Transaction amount.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getBalanceTransactionsList($id, GetDistributorTransactionsRequest $request)
    {
        return $this->posTerminalTransactionService->getDistributorBalanceTransactions($id, new GetDistributorTransactionsDto($request));
    }

    public function getCommissionTransactionsList($id, GetDistributorTransactionsRequest $request)
    {
        return $this->posTerminalTransactionService->getDistributorCommissionTransactions($id, new GetDistributorTransactionsDto($request));
    }

    /**
     * @OA\Post(
     *     path="/merchant/{id}/update-balance",
     *     operationId="updateMerchantBalance",
     *     tags={"Merchant"},
     *     summary="Update the balance of a merchant",
     *     description="Updates the balance of a specific merchant with a specified type and amount.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the merchant.",
     *         @OA\Schema(type="string", format="uuid", example="234528b4-b9ce-4b81-8d03-7a0f11296d5c")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"type", "amount"},
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 example="credit",
     *                 enum={"credit", "debit"},
     *                 description="The type of balance update: credit or debit."
     *             ),
     *             @OA\Property(
     *                 property="amount",
     *                 type="number",
     *                 example=1500,
     *                 description="The amount to update the balance by."
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="desc",
     *                 description="Optional description of the balance update."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function updateBalance($id, UpdateBalanceRequest $request)
    {
        return $this->posTerminalTransactionService->updateDistributorBalance($id, new UpdateBalanceDto($request));
    }

}
