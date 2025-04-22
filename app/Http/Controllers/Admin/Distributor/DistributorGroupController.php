<?php

namespace App\Http\Controllers\Admin\Distributor;

use App\DTO\Admin\Merchant\CreateMerchantGroupDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Distributor\DistributorGroupRequest;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class DistributorGroupController extends Controller
{
    public $distributorGroupService;

    /**
     * Attribute  Constructor.
     */
    public function __construct(DistributorGroupServiceInterface $distributorGroupService)
    {
        $this->distributorGroupService = $distributorGroupService;
    }

    /**
     * @OA\Get(
     *     path="/merchant-group",
     *     operationId="getMerchantGroups",
     *     tags={"Merchant Group"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return $this->distributorGroupService->index([]);
    }

    public function create()
    {

    }

    /**
     * @OA\Post(
     *     path="/merchant-group",
     *     operationId="createMerchantGroup",
     *     tags={"Merchant Group"},
     *     summary="Create a new Merchant Group",
     *     description="Creates a new Merchant Group with specified conditions and translations.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"is_require_all_conditions", "is_auto_assign", "is_active", "translations", "conditions"},
     *             @OA\Property(property="is_require_all_conditions", type="boolean", example=true, description="Indicates if all conditions are required."),
     *             @OA\Property(property="is_auto_assign", type="boolean", example=true, description="Specifies if merchants are auto-assigned."),
     *             @OA\Property(property="is_active", type="boolean", example=true, description="Status of the merchant group."),
     *             @OA\Property(
     *                 property="translations",
     *                 type="object",
     *                 description="Translations for different languages",
     *                 @OA\Property(
     *                     property="en",
     *                     type="object",
     *                     description="English translations",
     *                     @OA\Property(property="name", type="string", example="Golden Group", description="Name in English"),
     *                     @OA\Property(property="description", type="string", example="Hello", description="Description in English")
     *                 ),
     *                 @OA\Property(
     *                     property="ar",
     *                     type="object",
     *                     description="Arabic translations",
     *                     @OA\Property(property="name", type="string", example="المجموعة الذهبية", description="Name in Arabic"),
     *                     @OA\Property(property="description", type="string", example="اهلا", description="Description in Arabic")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="conditions",
     *                 type="array",
     *                 description="List of conditions for the merchant group",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"condition_type", "prefix", "value"},
     *                     @OA\Property(property="condition_type", type="string", example="orders_count", description="Type of condition (e.g., orders_count, orders_value, account_created_from, zone)"),
     *                     @OA\Property(property="prefix", type="string", example="greater_than", description="Condition prefix (e.g., greater_than, lower_than, between)"),
     *                     @OA\Property(property="value", type="string", example="50", description="Value of the condition. For 'between', use a range like '180000 - 200000'")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Merchant group created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Merchant group created successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access.")
     *         )
     *     )
     * )
     */

    public function store(DistributorGroupRequest $request)
    {
        return $this->distributorGroupService->store(new CreateMerchantGroupDto($request));
    }

    /**
     * @OA\Get(
     *     path="/merchant-group/form/fill",
     *     operationId="fillGroupRequiredData",
     *     tags={"Merchant Group"},
     *     summary="Ger Required data to fill form",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function fill()
    {
        return $this->distributorGroupService->fill();
    }

    /**
     * @OA\Patch(
     *     path="/merchant-group/{id}",
     *     operationId="updateMerchantGroup",
     *     tags={"Merchant Group"},
     *     summary="Update Merchant Group",
     *     description="Creates a new Merchant Group with specified conditions and translations.",
     *     security={{"BearerAuth":{}}},
     *          @OA\Parameter(
     *            name="id",
     *            in="path",
     *            required=true,
     *            description="Merchant ID to update",
     *            @OA\Schema(
     *                type="string",
     *                format="string",
     *                example=50
     *            )
     *        ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"is_require_all_conditions", "is_auto_assign", "is_active", "translations", "conditions"},
     *             @OA\Property(property="is_require_all_conditions", type="boolean", example=true, description="Indicates if all conditions are required."),
     *             @OA\Property(property="is_auto_assign", type="boolean", example=true, description="Specifies if merchants are auto-assigned."),
     *             @OA\Property(property="is_active", type="boolean", example=true, description="Status of the merchant group."),
     *             @OA\Property(
     *                 property="translations",
     *                 type="object",
     *                 description="Translations for different languages",
     *                 @OA\Property(
     *                     property="en",
     *                     type="object",
     *                     description="English translations",
     *                     @OA\Property(property="name", type="string", example="Golden Group", description="Name in English"),
     *                     @OA\Property(property="description", type="string", example="Hello", description="Description in English")
     *                 ),
     *                 @OA\Property(
     *                     property="ar",
     *                     type="object",
     *                     description="Arabic translations",
     *                     @OA\Property(property="name", type="string", example="المجموعة الذهبية", description="Name in Arabic"),
     *                     @OA\Property(property="description", type="string", example="اهلا", description="Description in Arabic")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="conditions",
     *                 type="array",
     *                 description="List of conditions for the merchant group",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"condition_type", "prefix", "value"},
     *                     @OA\Property(property="condition_type", type="string", example="orders_count", description="Type of condition (e.g., orders_count, orders_value, account_created_from, zone)"),
     *                     @OA\Property(property="prefix", type="string", example="greater_than", description="Condition prefix (e.g., greater_than, lower_than, between)"),
     *                     @OA\Property(property="value", type="string", example="50", description="Value of the condition. For 'between', use a range like '180000 - 200000'")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Merchant group created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Merchant group created successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access.")
     *         )
     *     )
     * )
     */
    public function update(DistributorGroupRequest $request, $id)
    {
        return $this->distributorGroupService->update($id, new CreateMerchantGroupDto($request));
    }

    /**
     * @OA\Delete(
     *     path="/merchant-group/{id}",
     *     operationId="destroyMerchantGroup",
     *     tags={"Merchant Group"},
     *     summary="Delete Merchant Group",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Merchant Group ID to delete",
     *          @OA\Schema(
     *              type="string",
     *              format="integer",
     *              example=50
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->distributorGroupService->delete($id);
    }

    /**
     * @OA\Get(
     *     path="/merchant-group/{id}",
     *     operationId="showMerchantGroup",
     *     tags={"Merchant Group"},
     *     summary="Show Merchant Group",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Merchant Group ID to show",
     *          @OA\Schema(
     *              type="integer",
     *              example=50
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        return $this->distributorGroupService->show($id);
    }

}
