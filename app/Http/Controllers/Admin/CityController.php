<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Services\GeoLocation\CityService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CityController extends Controller
{
    public $cityService;

    /**
     * City Constructor.
     */
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }


    /**
     * @OA\Get(
     *     path="/cities",
     *     operationId="getCities",
     *     tags={"City"},
     *     summary="Retrieve a list of cities",
     *     description="Fetches a list of all available cities.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function index(Request $request)
    {
        return $this->cityService->getAllCities($request);
    }

    /**
     * @OA\Get(
     *     path="/getAllCitiesForm",
     *     operationId="getAllCitiesForm",
     *     tags={"City"},
     *     summary="Retrieve all cities in form-ready format",
     *     description="Fetches a list of all cities formatted for forms or dropdowns.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getAllCitiesForm(Request $request)
    {
        return $this->cityService->getAllCitiesForm($request);
    }

    /**
     * @OA\Post(
     *     path="/cities",
     *     operationId="storeCity",
     *     tags={"City"},
     *     summary="Store a new city",
     *     description="Creates a new city with localized names and region ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 example={"1": "test", "3": "nnnn"},
     *                 description="Localized names of the city. Keys represent language IDs."
     *             ),
     *             @OA\Property(
     *                 property="region_id",
     *                 type="integer",
     *                 example=3,
     *                 description="The ID of the region to which the city belongs."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function store(CityRequest $request)
    {
        return $this->cityService->storeCity($request);
    }

    /**
     * @OA\Get(
     *     path="/cities/{id}",
     *     operationId="showCityDetails",
     *     tags={"City"},
     *     summary="Show city details",
     *     description="Fetches the details of a specific city using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the city to retrieve.",
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
        return $this->cityService->show($id);
    }


    /**
     * @OA\Post(
     *     path="/cities/{id}",
     *     operationId="updateCity",
     *     tags={"City"},
     *     summary="Update an existing city",
     *     description="Updates the localized names and region ID of a specific city.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the city to update.",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 example={"1": "test", "3": "nnnn"},
     *                 description="Localized names of the city. Keys represent language IDs."
     *             ),
     *             @OA\Property(
     *                 property="region_id",
     *                 type="integer",
     *                 example=3,
     *                 description="The ID of the region to which the city belongs."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function update(CityRequest $request, int $id)
    {
        return $this->cityService->updateCity($request,$id);
    }

    /**
     * @OA\Delete(
     *     path="/cities/{id}",
     *     operationId="deleteCity",
     *     tags={"City"},
     *     summary="Delete a city",
     *     description="Deletes a specific city using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the city to delete.",
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
        return $this->cityService->deleteCity($id);

    }

}
