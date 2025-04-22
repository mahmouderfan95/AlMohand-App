<?php

namespace App\Http\Controllers\Admin\PosTerminal;

use App\DTO\Admin\PosTerminal\GetPosTerminalTransactionsDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PosTerminalRequests\GetPosTransactionsRequest;
use App\Interfaces\ServicesInterfaces\BalanceLog\BalanceLogServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalTransactionServiceInterface;
use App\Services\BalanceLog\BalanceLogService;
use OpenApi\Annotations as OA;

class PosTerminalTransactionsController extends Controller
{
    public PosTerminalTransactionServiceInterface $posTerminalTransactionService;
    public BalanceLogService $balanceLogService;

    /**
     * Attribute  Constructor.
     */
    public function __construct(PosTerminalTransactionServiceInterface $posTerminalTransactionService,
                                BalanceLogServiceInterface $balanceLogService)
    {
        $this->posTerminalTransactionService = $posTerminalTransactionService;
        $this->balanceLogService = $balanceLogService;
    }

    /**
     * @OA\Post(
     *     path="/merchant/pos-terminal/{id}/transactions/balance",
     *     operationId="getMerchantPosTerminalBalanceTransactions",
     *     tags={"POS Terminal Transactions"},
     *     summary="Retrieve balance transaction for a specific POS terminal",
     *     description="Fetches the transaction balance for a specific POS terminal of a merchant with optional filters.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal.",
     *         @OA\Schema(type="string", format="uuid", example="11c5ccf0-2c2f-4f46-a17f-799143b55b3d")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"merchant_id"},
     *             @OA\Property(
     *                 property="merchant_id",
     *                 type="string",
     *                 format="uuid",
     *                 example="234528b4-b9ce-4b81-8d03-7a0f11296d5c",
     *                 description="The UUID of the merchant."
     *             ),
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
    public function getBalanceTransactionsList($id, GetPosTransactionsRequest $request)
    {
        return $this->posTerminalTransactionService->getPosBalanceTransactions($id, new GetPosTerminalTransactionsDto($request));
    }

    /**
     * @OA\Post(
     *     path="/merchant/pos-terminal/{id}/transactions/sales",
     *     operationId="getMerchantPosTerminalSalesTransaction",
     *     tags={"POS Terminal Transactions"},
     *     summary="Retrieve sales transactions for a specific POS terminal",
     *     description="Fetches the sales transactions for a specific POS terminal of a merchant with optional filters.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal.",
     *         @OA\Schema(type="string", format="uuid", example="11c5ccf0-2c2f-4f46-a17f-799143b55b3d")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"merchant_id"},
     *             @OA\Property(
     *                 property="merchant_id",
     *                 type="string",
     *                 format="uuid",
     *                 example="234528b4-b9ce-4b81-8d03-7a0f11296d5c",
     *                 description="The UUID of the merchant."
     *             ),
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
    public function getSalesTransactionsList($id, GetPosTransactionsRequest $request)
    {
        return $this->posTerminalTransactionService->getPosSalesTransactions($id, new GetPosTerminalTransactionsDto($request));
    }

    public function getCommissionTransactionsList($id)
    {

    }
}
