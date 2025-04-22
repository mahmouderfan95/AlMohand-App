<?php

namespace App\Http\Controllers\Admin\BalanceRequest;

use App\DTO\Admin\BalanceRequest\BalanceRequestStatusDto;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BalanceRequest\UpdateBalanceRequest;
use App\Interfaces\ServicesInterfaces\BalanceRequest\BalanceRequestServiceInterface;
use OpenApi\Annotations as OA;

class BalanceRequestController extends Controller
{
    public BalanceRequestServiceInterface $balanceRequestService;

    /**
     * Attribute  Constructor.
     */
    public function __construct(BalanceRequestServiceInterface $balanceRequestService)
    {
        $this->balanceRequestService = $balanceRequestService;
    }

    /**
     * @OA\Post(
     *     path="/balance-request",
     *     operationId="getBalanceRequests",
     *     tags={"Balance Request"},
     *     summary="Retrieve balance requests",
     *     description="Fetches balance requests with optional filtering.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="filters",
     *                 type="object",
     *                 description="Optional filters to narrow down the search.",
     *                 @OA\Property(
     *                     property="keyword",
     *                     type="string",
     *                     example="pos-3",
     *                     description="Search by keyword."
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
    public function index()
    {
        return $this->balanceRequestService->index([]);
    }

    /**
     * @OA\Post(
     *     path="/balance-request/{id}/update",
     *     operationId="updateBalanceRequestStatus",
     *     tags={"Balance Request"},
     *     summary="Update balance request status",
     *     description="Updates the status of a specific balance request.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the balance request to update.",
     *         @OA\Schema(type="string", format="uuid", example="03520709-5438-4909-bc7d-be4143c3ea8e")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"status"},
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="accepted",
     *                 description="The new status of the balance request."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function update(UpdateBalanceRequest $request, $id)
    {
        return $this->balanceRequestService->update($id, new BalanceRequestStatusDto($request));
    }
}
