<?php

namespace App\Http\Controllers\Admin\Distributor;

use App\DTO\Admin\Merchant\AddDistributorPosDTO;
use App\DTO\Admin\Merchant\CreateMerchantDto;
use App\DTO\Admin\Merchant\GetDistributorPosDto;
use App\DTO\Admin\Merchant\UpdateBalanceDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Distributor\AddDistributorPosRequest;
use App\Http\Requests\Admin\Distributor\DistributorPosListRequest;
use App\Http\Requests\Admin\Distributor\DistributorRequest;
use App\Http\Requests\Admin\Distributor\UpdateBalanceRequest;
use App\Interfaces\ServicesInterfaces\BalanceLog\BalanceLogServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorPosTerminalServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalTransactionServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_ADMIN_HOST,
 *     description="Admin API Server"
 * )
 */
class DistributorPosTerminalController extends Controller
{
    /**
     * Attribute  Constructor.
     */
    public function __construct(private readonly DistributorPosTerminalServiceInterface $distributorPosTerminalService,
                                private readonly PosTerminalTransactionServiceInterface $posTerminalTransactionService,
                                private readonly BalanceLogServiceInterface $balanceLogService,
    )
    {
    }

    /**
     * @OA\Post(
     *     path="/merchant/pos-terminal",
     *     operationId="getMerchantPosTerminals",
     *     tags={"Merchant POS Terminal"},
     *     summary="Retrieve merchant POS terminals",
     *     description="Fetches a list of POS terminals for a specific merchant, filtered by optional parameters.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="isPaginate",
     *         in="query",
     *         required=false,
     *         description="If false, retrieves all data without pagination.",
     *         @OA\Schema(type="boolean", example=false)
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
     *                 description="Optional filters to narrow down the search.",
     *                 @OA\Property(property="branch_name", type="string", example="jeddah", description="Branch name to filter by."),
     *                 @OA\Property(property="address", type="string", example="jeddah", description="Address to filter by."),
     *                 @OA\Property(property="receiver_phone", type="string", example="96656565656", description="Receiver's phone number."),
     *                 @OA\Property(property="receiver_name", type="string", example="Ahmed", description="Receiver's name."),
     *                 @OA\Property(
     *                     property="pos_terminal_id",
     *                     type="string",
     *                     format="uuid",
     *                     example="e1045a5b-870e-49d7-81c5-dea3043f76b3",
     *                     description="The UUID of the POS terminal."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getPosTerminalsList(DistributorPosListRequest $request)
    {
        return $this->distributorPosTerminalService->getPosTerminalsList(new GetDistributorPosDto($request));
    }

    /**
     * @OA\Post(
     *     path="/merchant/pos-terminal/create",
     *     operationId="createMerchantPosTerminal",
     *     tags={"Merchant POS Terminal"},
     *     summary="Create a new merchant POS terminal",
     *     description="Creates a new POS terminal for a specific merchant with optional details.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"merchant_id", "pos_terminal_id"},
     *             @OA\Property(
     *                 property="merchant_id",
     *                 type="string",
     *                 format="uuid",
     *                 example="234528b4-b9ce-4b81-8d03-7a0f11296d5c",
     *                 description="The UUID of the merchant."
     *             ),
     *             @OA\Property(
     *                 property="pos_terminal_id",
     *                 type="string",
     *                 format="uuid",
     *                 example="e1045a5b-870e-49d7-81c5-dea3043f76b3",
     *                 description="The UUID of the POS terminal."
     *             ),
     *             @OA\Property(
     *                 property="branch_name",
     *                 type="string",
     *                 example="jeddah",
     *                 description="The branch name (optional)."
     *             ),
     *             @OA\Property(
     *                 property="address",
     *                 type="string",
     *                 example="jeddah",
     *                 description="The address of the POS terminal (optional)."
     *             ),
     *             @OA\Property(
     *                 property="receiver_phone",
     *                 type="string",
     *                 example="96656565656",
     *                 description="The phone number of the receiver (optional)."
     *             ),
     *             @OA\Property(
     *                 property="receiver_name",
     *                 type="string",
     *                 example="Ahmed",
     *                 description="The name of the receiver (optional)."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function create(AddDistributorPosRequest $request)
    {
        return $this->distributorPosTerminalService->store(new AddDistributorPosDTO($request));
    }

    /**
     * @OA\Get(
     *     path="/merchant/pos-terminal/{id}",
     *     operationId="getMerchantPosTerminalDetails",
     *     tags={"Merchant POS Terminal"},
     *     summary="Get merchant POS terminal details",
     *     description="Fetches the details of a specific POS terminal for a merchant using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal to retrieve.",
     *         @OA\Schema(type="string", format="uuid", example="e1045a5b-870e-49d7-81c5-dea3043f76b3")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->distributorPosTerminalService->show($id);
    }

    /**
     * @OA\Post(
     *     path="/merchant/pos-terminal/{id}/update-balance",
     *     operationId="updatePosTerminalBalance",
     *     tags={"Merchant POS Terminal"},
     *     summary="Update the balance of a POS terminal",
     *     description="Updates the balance of a specific POS terminal with a specified type and amount.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal.",
     *         @OA\Schema(type="string", format="uuid", example="77ac61f6-86b1-42ce-b301-0d27efe07bd0")
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
        return $this->posTerminalTransactionService->updatePosBalance($id, new UpdateBalanceDto($request));
    }

    /**
     * @OA\Post(
     *     path="/merchant/pos-terminal/{id}/update-status",
     *     operationId="updatePosTerminalStatus",
     *     tags={"Merchant POS Terminal"},
     *     summary="Update the status of a POS terminal",
     *     description="Updates the active status of a specific POS terminal.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal.",
     *         @OA\Schema(type="string", format="uuid", example="77ac61f6-86b1-42ce-b301-0d27efe07bd0")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"is_active"},
     *             @OA\Property(
     *                 property="is_active",
     *                 type="boolean",
     *                 example=false,
     *                 description="The active status of the POS terminal."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['is_active' => 'required|bool']);
        return $this->distributorPosTerminalService->updateStatus($id, $request->get('is_active'));
    }

    /**
     * @OA\Delete(
     *     path="/merchant/pos-terminal/{id}",
     *     operationId="deleteMerchantPosTerminal",
     *     tags={"Merchant POS Terminal"},
     *     summary="Delete a POS terminal",
     *     description="Deletes a specific POS terminal using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal to delete.",
     *         @OA\Schema(type="string", format="uuid", example="87114b9e-0358-4113-8fdb-a14db2e80083")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function delete($id)
    {
        return $this->distributorPosTerminalService->delete($id);
    }

    /**
     * @OA\Get(
     *     path="/merchant/pos-terminal/{id}/transactions/points",
     *     operationId="getPosTerminalPointsTransactions",
     *     tags={"POS Terminal Transactions"},
     *     summary="Retrieve POS terminal points transactions",
     *     description="Fetches the points transactions for a specific POS terminal of a merchant.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the POS terminal.",
     *         @OA\Schema(type="string", format="uuid", example="eeff7d42-8f37-4a77-b9bc-c48aa3e8d83c")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getPointsTransactionsList($id)
    {
        return $this->balanceLogService->getPosPointsTransactions($id);
    }
}
