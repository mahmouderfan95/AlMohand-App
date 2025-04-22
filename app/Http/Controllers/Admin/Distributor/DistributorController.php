<?php

namespace App\Http\Controllers\Admin\Distributor;

use App\DTO\Admin\Merchant\CreateMerchantDto;
use App\DTO\Admin\Merchant\GetDistributorPosDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Distributor\AddDistributorPosRequest;
use App\Http\Requests\Admin\Distributor\DistributorPosListRequest;
use App\Http\Requests\Admin\Distributor\DistributorRequest;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class DistributorController extends Controller
{
    public $distributorService;

    /**
     * Attribute  Constructor.
     */
    public function __construct(DistributorServiceInterface $distributorService)
    {
        $this->distributorService = $distributorService;
    }

    /**
     * @OA\Get(
     *     path="/merchant",
     *     operationId="getMerchants",
     *     tags={"Merchant"},
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
        return $this->distributorService->index([]);
    }

    public function create()
    {

    }

    /**
     * @OA\Get(
     *     path="/merchant/{id}",
     *     operationId="showMerchant",
     *     tags={"Merchant"},
     *     summary="Show Merchant",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Merchant ID to show",
     *          @OA\Schema(
     *              type="string",
     *              example="edfd82b7-361b-492d-9dd3-e463e6b2566e"
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
        return $this->distributorService->show($id);
    }

    /**
     * @OA\Post(
     *     path="/merchant",
     *     operationId="storeMerchant",
     *     tags={"Merchant"},
     *     summary="Store a new Merchant",
     *     description="Creates a new Merchant and uploads associated files.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"distributor_group_id", "zone_id", "city_id", "manager_name", "owner_name", "phone", "commercial_register", "tax_card", "sales_rep_id"},
     *                 @OA\Property(property="distributor_group_id", type="integer", example=1, description="Distributor group ID"),
     *                 @OA\Property(property="zone_id", type="integer", example=1, description="Zone ID"),
     *                 @OA\Property(property="city_id", type="integer", example=2, description="City ID"),
     *                 @OA\Property(property="manager_name", type="string", example="John Doe", description="Manager's name"),
     *                 @OA\Property(property="owner_name", type="string", example="Jane Doe", description="Owner's name"),
     *                 @OA\Property(property="phone", type="string", example="+123456789", description="Phone number"),
     *                 @OA\Property(property="email", type="string", format="email", nullable=true, example="merchant@example.com", description="Email address"),
     *                 @OA\Property(property="address", type="string", nullable=true, example="123 Street, City", description="Address"),
     *                 @OA\Property(property="commercial_register", type="string", example="123456789", description="Commercial register number"),
     *                 @OA\Property(property="tax_card", type="string", example="987654321", description="Tax card number"),
     *                 @OA\Property(property="sales_rep_id", type="integer", example=1 , description="Sales Rep ID",),
     *                 @OA\Property(
     *                     property="translations[en][name]",
     *                     type="string",
     *                     example="Hoda Group",
     *                     description="Merchant name in English"
     *                 ),
     *                 @OA\Property(
     *                     property="translations[en][commercial_register_name]",
     *                     type="string",
     *                     example="Al Hoda",
     *                     description="Commercial register name in English"
     *                 ),
     *                 @OA\Property(
     *                     property="translations[ar][name]",
     *                     type="string",
     *                     example="أسواق الهدى",
     *                     description="Merchant name in Arabic"
     *                 ),
     *                 @OA\Property(
     *                     property="translations[ar][commercial_register_name]",
     *                     type="string",
     *                     example="أسواق الهدى السعودية",
     *                     description="Commercial register name in Arabic"
     *                 ),
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     format="binary",
     *                     description="Merchant logo file"
     *                 ),
     *                 @OA\Property(
     *                     property="commercial_register_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Files for commercial register (multiple allowed)"
     *                 ),
     *                 @OA\Property(
     *                     property="tax_card_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Files for tax card (multiple allowed)"
     *                 ),
     *                 @OA\Property(
     *                     property="identity_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Files for identity verification (multiple allowed)"
     *                 ),
     *                 @OA\Property(
     *                     property="more_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Additional files (multiple allowed)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Merchant created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Merchant created successfully."),
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
    public function store(DistributorRequest $request)
    {
        return $this->distributorService->store(new CreateMerchantDto($request));
    }

    public function edit($id)
    {
    }

    /**
     * @OA\Post(
     *     path="/merchant/{id}/update",
     *     operationId="updateMerchant",
     *     tags={"Merchant"},
     *     summary="Update Merchant",
     *     description="Update Merchant and uploads associated files.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="Merchant ID to update",
     *           @OA\Schema(
     *               type="string",
     *               format="uuid",
     *               example="edfd82b7-361b-492d-9dd3-e463e6b2566e"
     *           )
     *       ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"distributor_group_id", "zone_id", "city_id", "manager_name", "owner_name", "phone", "commercial_register", "tax_card", "sales_rep_id"},
     *                 @OA\Property(property="distributor_group_id", type="integer", example=1, description="Distributor group ID"),
     *                 @OA\Property(property="zone_id", type="integer", example=1, description="Zone ID"),
     *                 @OA\Property(property="city_id", type="integer", example=2, description="City ID"),
     *                 @OA\Property(property="manager_name", type="string", example="John Doe", description="Manager's name"),
     *                 @OA\Property(property="owner_name", type="string", example="Jane Doe", description="Owner's name"),
     *                 @OA\Property(property="phone", type="string", example="+123456789", description="Phone number"),
     *                 @OA\Property(property="email", type="string", format="email", nullable=true, example="merchant@example.com", description="Email address"),
     *                 @OA\Property(property="address", type="string", nullable=true, example="123 Street, City", description="Address"),
     *                 @OA\Property(property="commercial_register", type="string", example="123456789", description="Commercial register number"),
     *                 @OA\Property(property="tax_card", type="string", example="987654321", description="Tax card number"),
     *                 @OA\Property(property="sales_rep_id", type="integer", example=1, description="Sales Rep ID",),
     *                 @OA\Property(
     *                     property="translations[en][name]",
     *                     type="string",
     *                     example="Hoda Group",
     *                     description="Merchant name in English"
     *                 ),
     *                 @OA\Property(
     *                     property="translations[en][commercial_register_name]",
     *                     type="string",
     *                     example="Al Hoda",
     *                     description="Commercial register name in English"
     *                 ),
     *                 @OA\Property(
     *                     property="translations[ar][name]",
     *                     type="string",
     *                     example="أسواق الهدى",
     *                     description="Merchant name in Arabic"
     *                 ),
     *                 @OA\Property(
     *                     property="translations[ar][commercial_register_name]",
     *                     type="string",
     *                     example="أسواق الهدى السعودية",
     *                     description="Commercial register name in Arabic"
     *                 ),
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     format="binary",
     *                     description="Merchant logo file"
     *                 ),
     *                 @OA\Property(
     *                     property="commercial_register_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Files for commercial register (multiple allowed)"
     *                 ),
     *                 @OA\Property(
     *                     property="tax_card_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Files for tax card (multiple allowed)"
     *                 ),
     *                 @OA\Property(
     *                     property="identity_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Files for identity verification (multiple allowed)"
     *                 ),
     *                 @OA\Property(
     *                     property="more_files[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Additional files (multiple allowed)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Merchant updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Merchant created successfully."),
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
    public function update(DistributorRequest $request, $id)
    {
        return $this->distributorService->update($id, new CreateMerchantDto($request));
    }

    /**
     * @OA\Post(
     *     path="/merchant/{id}/update-status",
     *     operationId="updateMerchantStatus",
     *     tags={"Merchant"},
     *     summary="Update the status of a merchant",
     *     description="Updates the active status of a specific merchant.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the merchant.",
     *         @OA\Schema(type="string", format="uuid", example="a17c22a6-0dc9-4597-9ee9-96d5f9a0729b")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"is_active"},
     *             @OA\Property(
     *                 property="is_active",
     *                 type="boolean",
     *                 example=true,
     *                 description="The active status of the merchant."
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
        return $this->distributorService->updateStatus($id, $request->get('is_active'));
    }

    /**
     * @OA\Delete(
     *     path="/merchant/{id}",
     *     operationId="destroyMerchant",
     *     tags={"Merchant"},
     *     summary="Delete Merchant",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Merchant ID to delete",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid",
     *              example="edfd82b7-361b-492d-9dd3-e463e6b2566e"
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
        return $this->distributorService->delete($id);
    }


    /**
     * @OA\Post(
     *     path="/merchant/attachment/{id}/delete",
     *     operationId="deleteMerchantAttachment",
     *     tags={"Merchant"},
     *     summary="Delete Merchant Attachment",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *           name="attachment_id",
     *           in="path",
     *           required=true,
     *           description="Attachment ID to delete",
     *           @OA\Schema(
     *               type="string",
     *               format="uuid",
     *               example="edfd82b7-361b-492d-9dd3-e463e6b2566e"
     *           )
     *       ),
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
    public function deleteAttachment($id)
    {
        return $this->distributorService->deleteAttachment($id);
    }

    /**
     * @OA\Get(
     *     path="/merchant/form/fill",
     *     operationId="fillRequiredData",
     *     tags={"Merchant"},
     *     summary="Get Required data to fill form",
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
    public function fillRequiredData()
    {
        return $this->distributorService->fillRequiredData();
    }


}
