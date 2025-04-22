<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequests\MainSettingRequest;
use App\Http\Requests\Admin\SettingRequests\NotificationSettingRequest;
use App\Http\Requests\Admin\SettingRequests\PointsCommissionSettingRequest;
use App\Services\Admin\SettingService;
use App\Settings\CommissionPointsSettings;
use App\Settings\CoreSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class SettingController extends Controller
{

    public function __construct(private SettingService $settingService)
    {}

    public function mainSettings()
    {
        return $this->settingService->mainSettings();
    }

    public function updateMainSettings(MainSettingRequest $request)
    {
        return $this->settingService->updateMainSettings($request);
    }

    /**
     * @OA\Get(
     *     path="/settings/{group}",
     *     operationId="getSettingsByGroup",
     *     tags={"Settings"},
     *     summary="Retrieve settings by group",
     *     description="Fetches system settings based on the specified group name.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="The group name of the settings (e.g., points_commission).",
     *         @OA\Schema(type="string", example="points_commission")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved settings."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getByGroup($group)
    {
        return $this->settingService->getByGroup($group);
    }

    /**
     * @OA\Post(
     *     path="/settings/points_commission",
     *     operationId="updatePointsCommissionSettings",
     *     tags={"Settings"},
     *     summary="Update points & commission settings",
     *     description="Updates the settings related to points and commission calculations.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={
     *                 "apply_on_selling_by_mada",
     *                 "apply_on_recharging_by_mada",
     *                 "amount_per_points_redeem",
     *                 "points_per_amount_redeem",
     *                 "amount_per_points",
     *                 "points_per_amount",
     *                 "mada_fees",
     *                 "mada_added_tax"
     *             },
     *             @OA\Property(
     *                 property="apply_on_selling_by_mada",
     *                 type="integer",
     *                 example=0
     *             ),
     *             @OA\Property(
     *                 property="apply_on_recharging_by_mada",
     *                 type="integer",
     *                 example=0
     *             ),
     *             @OA\Property(
     *                 property="amount_per_points_redeem",
     *                 type="number",
     *                 format="float",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="points_per_amount_redeem",
     *                 type="integer",
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="amount_per_points",
     *                 type="number",
     *                 format="float",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="points_per_amount",
     *                 type="integer",
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="mada_fees",
     *                 type="number",
     *                 format="float",
     *                 example=0.01
     *             ),
     *             @OA\Property(
     *                 property="mada_added_tax",
     *                 type="number",
     *                 format="float",
     *                 example=0.0018
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Settings updated successfully."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function updateCommissionSetting(PointsCommissionSettingRequest $request, $group)
    {
        return $this->settingService->updatePointCommissionSetting($request, $group);
    }

}
