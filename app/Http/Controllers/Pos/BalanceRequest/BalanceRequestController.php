<?php

namespace App\Http\Controllers\Pos\BalanceRequest;

use App\DTO\Pos\Auth\PosLoginDto;
use App\DTO\Pos\BalanceRequest\BalanceRequestDto;
use App\DTO\Pos\BalanceRequest\MadaCallbackDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Auth\LoginRequest;
use App\Http\Requests\Pos\BalanceRequest\MadaCallbackRequest;
use App\Http\Requests\Pos\BalanceRequest\RequestBalanceRequest;
use App\Interfaces\ServicesInterfaces\BalanceRequest\BalanceRequestServiceInterface;
use OpenApi\Annotations as OA;


class BalanceRequestController extends Controller
{
    public function __construct(private readonly BalanceRequestServiceInterface $balanceRequestService)
    {
    }

    /**
     * @OA\Post(
     *     path="/balance-request",
     *     operationId="createBalanceRequest",
     *     tags={"Balance Request"},
     *     summary="Create a balance request",
     *     description="Creates a new balance request with the specified amount.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"amount"},
     *             @OA\Property(
     *                 property="amount",
     *                 type="number",
     *                 example=200,
     *                 description="The amount for the balance request."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function create(RequestBalanceRequest $request)
    {
        return $this->balanceRequestService->store(new BalanceRequestDto($request));
    }


    /**
     * @OA\Post(
     *     path="/balance-request/callback",
     *     operationId="balanceRequestCallback",
     *     tags={"Balance Request"},
     *     summary="Handle balance request callback",
     *     description="Processes the callback from the payment gateway for a balance request.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"payment_code", "payment_gateway", "payment_gateway_response"},
     *             @OA\Property(
     *                 property="payment_code",
     *                 type="string",
     *                 example="25011590133205",
     *                 description="The unique code for the payment request."
     *             ),
     *             @OA\Property(
     *                 property="payment_gateway",
     *                 type="string",
     *                 example="mada",
     *                 description="The name of the payment gateway."
     *             ),
     *             @OA\Property(
     *                 property="payment_gateway_response",
     *                 type="object",
     *                 description="The detailed response from the payment gateway.",
     *                 example={}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function callback(MadaCallbackRequest $request)
    {
        return $this->balanceRequestService->madaCallback(new MadaCallbackDto($request));
    }
}
