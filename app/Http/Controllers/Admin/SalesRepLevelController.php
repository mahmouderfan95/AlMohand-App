<?php

namespace App\Http\Controllers\Admin;

use App\DTO\BaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SalesRepLevelRequests\SalesRepLevelStatusRequest;
use App\Interfaces\ServicesInterfaces\SalesRepLevel\SalesRepLevelServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SalesRepLevelRequests\SalesRepLevelRequest;
use App\Services\SalesRepLevel\SalesRepLevelService;

class SalesRepLevelController extends Controller
{
    public $salesRepLevelService;

    /**
     * SalesRepLevel  Constructor.
     */
    public function __construct(SalesRepLevelServiceInterface $salesRepLevelService)
    {
        $this->salesRepLevelService = $salesRepLevelService;
    }


    /**
     * @OA\Get(
     *     path="/salesRepLevels",
     *     operationId="getSalesRepLevels",
     *     tags={"SalesRepLevel"},
     *     summary="Retrieve a list of salesRepLevels",
     *     description="Fetches a list of all available salesRepLevels, with optional sorting, filtering, and pagination.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="is_paginate",
     *         in="query",
     *         description="Set to 1 for paginated results or 0 for full list",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1}, default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of salesRepLevels",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Electronics"),
     *                 @OA\Property(property="code", type="string", example="code Description"),
     *                 @OA\Property(property="status", type="string", example="status Description"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->salesRepLevelService->index($request);
    }


    /**
     * @OA\Post(
     *     path="/salesRepLevels",
     *     operationId="storeSalesRepLevel",
     *     tags={"SalesRepLevel"},
     *     summary="Create a new salesRepLevel",
     *     description="Creates a new salesRepLevel with the provided details.",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="SalesRepLevel data that needs to be stored",
     *         @OA\JsonContent(
     *             required={"name", "status", "code"},
     *             @OA\Property(
     *                  property="name",
     *                  type="object",
     *                  example={"1": "test", "2": "nnnn"},
     *                  description="Localized names of the SalesRepLevel. Keys represent language IDs."
     *              ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"active", "inactive"},
     *                 example="active",
     *                 description="Status of the salesRepLevel"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="SRL001",
     *                 description="Unique code for the salesRepLevel"
     *             ),
     *             @OA\Property(
     *                 property="permissions",
     *                 type="Array",
     *                 example="[]",
     *                 description="permissions code for the salesRepLevel"
     *             ),
     *             @OA\Property(
     *                 property="parent_id",
     *                 type="integer",
     *                 nullable=true,
     *                 example=1,
     *                 description="ID of the parent salesRepLevel, if any"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="SalesRepLevel created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 @OA\Property(property="en", type="string", example="Electronics"),
     *                 @OA\Property(property="ar", type="string", example="إلكترونيات")
     *             ),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="code", type="string", example="SRL001"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=0),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function store(SalesRepLevelRequest $request)
    {
        return $this->salesRepLevelService->store(new BaseDTO($request));
    }


    /**
     * @OA\Get(
     *     path="/salesRepLevels/{id}",
     *     operationId="getSalesRepLevelById",
     *     tags={"SalesRepLevel"},
     *     summary="Retrieve a single salesRepLevel",
     *     description="Fetches details of a specific salesRepLevel by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepLevel ID",
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
        return $this->salesRepLevelService->show($id);
    }



    /**
     * @OA\Post (
     *     path="/salesRepLevels/{id}",
     *     operationId="updateSalesRepLevel",
     *     tags={"SalesRepLevel"},
     *     summary="Update an existing salesRepLevel",
     *     description="Updates details of a specific salesRepLevel by ID.",
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepLevel ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="SalesRepLevel data that needs to be updated",
     *         @OA\JsonContent(
     *             required={"name", "status", "code"},
     *             @OA\Property(
     *                   property="name",
     *                   type="object",
     *                   example={"1": "test", "2": "nnnn"},
     *                   description="Localized names of the SalesRepLevel. Keys represent language IDs."
     *               ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"active", "inactive"},
     *                 example="inactive",
     *                 description="Updated status of the salesRepLevel"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="SRL002",
     *                 description="Unique code for the salesRepLevel"
     *             ),
     *            @OA\Property(
     *                  property="permissions",
     *                  type="Array",
     *                  example="[]",
     *                  description="permissions code for the salesRepLevel"
     *              ),
     *             @OA\Property(
     *                 property="parent_id",
     *                 type="integer",
     *                 nullable=true,
     *                 example=1,
     *                 description="Updated ID of the parent salesRepLevel, if any"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="SalesRepLevel updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 @OA\Property(property="en", type="string", example="Updated Electronics"),
     *                 @OA\Property(property="ar", type="string", example="إلكترونيات محدثة")
     *             ),
     *             @OA\Property(property="status", type="string", example="inactive"),
     *             @OA\Property(property="code", type="string", example="SRL002"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=1),
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

    public function update(SalesRepLevelRequest $request, int $id)
    {
        return $this->salesRepLevelService->update($id , new BaseDTO($request));
    }




    /**
     * @OA\Post(
     *     path="/salesRepLevel/update-status/{id}",
     *     operationId="updateSalesRepLevelStatus",
     *     tags={"SalesRepLevel"},
     *     summary="Update the status of a salesRepLevel",
     *     description="Updates the status of a specific salesRepLevel by ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepLevel ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated status",
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Status updated successfully"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function update_status(SalesRepLevelStatusRequest $request, int $id)
    {
        return $this->salesRepLevelService->update_status($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/salesRepLevels/{id}",
     *     operationId="deleteSalesRepLevel",
     *     tags={"SalesRepLevel"},
     *     summary="Delete a salesRepLevel",
     *     description="Deletes a salesRepLevel by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepLevel ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */


    public function destroy(int $id)
    {
        return $this->salesRepLevelService->delete($id);

    }

    /**
     * @OA\post(
     *     path="/salesRepLevel/destroy_selected",
     *     operationId="destroySelectedSalesRepLevels",
     *     tags={"SalesRepLevel"},
     *     summary="Delete selected salesRepLevels",
     *     description="Deletes multiple salesRepLevels by their IDs.",
     *     @OA\Parameter(
     *         name="ids",
     *         in="query",
     *         required=true,
     *         description="Comma-separated list of SalesRepLevel IDs to be deleted.",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="general_error")
     *         )
     *     )
     * )
     */
    public function destroy_selected(Request $request)
    {;
        return $this->salesRepLevelService->bulkDelete(array ($request->ids));
    }


    /**
     *
     * trash SalesRepLevel
     *
     */
    public function trash()
    {
        return $this->salesRepLevelService->trash();

    }

    /**
     *
     * trash SalesRepLevel
     *
     */
    public function restore(int $id)
    {
        return $this->salesRepLevelService->restore($id);

    }

}
