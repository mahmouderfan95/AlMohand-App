<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegionRequest;
use App\Services\GeoLocation\RegionService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class RegionController extends Controller
{
    public $regionService;

    /**
     * Region  Constructor.
     */
    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }


    /**
     * @OA\Get(
     *     path="/regions",
     *     operationId="getRegions",
     *     tags={"Region"},
     *     summary="Retrieve a list of regions",
     *     description="Fetches a list of all available regions.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function index(Request $request)
    {
        return $this->regionService->getAllRegions($request);
    }


    /**
     * @OA\Get(
     *     path="/getAllRegionsForm",
     *     operationId="getAllRegionsForm",
     *     tags={"Region"},
     *     summary="Retrieve all regions in form-ready format",
     *     description="Fetches a list of all regions formatted for forms or dropdowns.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function getAllRegionsForm(Request $request)
    {
        return $this->regionService->getAllRegionsForm($request);
    }


    /**
     * @OA\Post(
     *     path="/regions",
     *     operationId="storeRegion",
     *     tags={"Region"},
     *     summary="Store a new region",
     *     description="Creates a new region with localized names and the associated country ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 example={"1": "testd", "3": "nnnd"},
     *                 description="Localized names of the region. Keys represent language IDs."
     *             ),
     *             @OA\Property(
     *                 property="country_id",
     *                 type="integer",
     *                 example=2,
     *                 description="The ID of the country to which the region belongs."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function store(RegionRequest $request)
    {
        return $this->regionService->storeRegion($request);
    }

    /**
     * @OA\Get(
     *     path="/regions/{id}",
     *     operationId="showRegionDetails",
     *     tags={"Region"},
     *     summary="Show region details",
     *     description="Fetches the details of a specific region using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the region to retrieve.",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function show( $id)
    {
        return $this->regionService->show($id);
    }


    /**
     * @OA\Post(
     *     path="/regions/{id}",
     *     operationId="updateRegion",
     *     tags={"Region"},
     *     summary="Update an existing region",
     *     description="Updates the localized names and country ID of a specific region using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the region to update.",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 example={"1": "testd", "3": "nnnd"},
     *                 description="Localized names of the region. Keys represent language IDs."
     *             ),
     *             @OA\Property(
     *                 property="country_id",
     *                 type="integer",
     *                 example=2,
     *                 description="The ID of the country to which the region belongs."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function update(RegionRequest $request, int $id)
    {
        return $this->regionService->updateRegion($request,$id);
    }

    /**
     * @OA\Delete(
     *     path="/regions/{id}",
     *     operationId="deleteRegion",
     *     tags={"Region"},
     *     summary="Delete a region",
     *     description="Deletes a specific region using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the region to delete.",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function destroy(int $id)
    {
        return $this->regionService->deleteRegion($id);

    }

}
