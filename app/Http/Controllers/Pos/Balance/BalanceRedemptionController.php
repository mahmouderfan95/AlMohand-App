<?php

namespace App\Http\Controllers\Pos\Balance;

use App\DTO\Pos\Auth\PosLoginDto;
use App\DTO\Pos\BalanceRequest\BalanceRequestDto;
use App\Enums\BalanceLog\BalanceTypeStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Auth\LoginRequest;
use App\Http\Requests\Pos\BalanceRequest\RequestBalanceRequest;
use App\Interfaces\ServicesInterfaces\BalanceLog\BalanceLogServiceInterface;
use App\Interfaces\ServicesInterfaces\BalanceRequest\BalanceRequestServiceInterface;
use App\Traits\ApiResponseAble;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


class BalanceRedemptionController extends Controller
{
    use ApiResponseAble;

    public function __construct(private readonly BalanceLogServiceInterface $balanceLogService)
    {
    }

    /**
     * @OA\Post(
     *     path="/balance/{type}/redeem",
     *     operationId="redeemBalance",
     *     tags={"Balance"},
     *     summary="Redeem balance by type",
     *     description="Redeems balance for the specified type by deducting the specified amount. The type can be points or commission.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         description="The type of balance to redeem, e.g., points or commission.",
     *         @OA\Schema(type="string", enum={"points", "commission"}, example="points")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"amount"},
     *             @OA\Property(
     *                 property="amount",
     *                 type="integer",
     *                 example=100,
     *                 description="The amount to redeem from the specified balance type."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function redeem($type, Request $request)
    {
        if (empty($type) || !in_array($type, BalanceTypeStatusEnum::getList())) {
            return $this->validationErrorResponse(['type' => 'invalid type']);
        }
        $amount = $request->amount;
        if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
            return $this->validationErrorResponse(['amount' => 'invalid amount']);
        }
        $pos = auth('posApi')->user();
        return $this->balanceLogService->redeem($pos->pos_terminal_id, $type, $request->amount);
    }

    /**
     * @OA\Post(
     *     path="/balance/points",
     *     operationId="updateBalancePoints",
     *     tags={"Balance"},
     *     summary="Update balance points",
     *     description="Updates the balance by adding or deducting points.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"points"},
     *             @OA\Property(
     *                 property="points",
     *                 type="integer",
     *                 example=100,
     *                 description="The number of points to add or deduct from the balance."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getPointsValue(Request $request)
    {
        $points = $request->points;
        if (empty($points) || !is_numeric($points) || $points <= 0) {
            return $this->validationErrorResponse(['points' => 'invalid points']);
        }
        return $this->ApiSuccessResponse(['cashback_value' => $this->balanceLogService->getPointsCashbackValue($points)]);
    }
}
