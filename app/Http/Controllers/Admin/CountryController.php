<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Services\GeoLocation\CountryService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CountryController extends Controller
{
    public $countryService;

    /**
     * GeoLocation Constructor.
     */
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }


    /**
     * @OA\Get(
     *     path="/countries",
     *     operationId="getCountries",
     *     tags={"Country"},
     *     summary="Retrieve a list of countries",
     *     description="Fetches a list of all available countries.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function index(Request $request)
    {
        return $this->countryService->getAllCountries($request);
    }

    /**
     * @OA\Get(
     *     path="/getAllCountriesForm",
     *     operationId="getAllCountriesForm",
     *     tags={"Country"},
     *     summary="Retrieve all countries in form-ready format",
     *     description="Fetches a list of all countries formatted for forms or dropdowns.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function getAllCountriesForm(Request $request)
    {
        return $this->countryService->getAllCountriesForm($request);
    }



    /**
     * @OA\Post(
     *     path="/countries",
     *     operationId="storeCountry",
     *     tags={"Country"},
     *     summary="Store a new country",
     *     description="Creates a new country with provided code and names in multiple languages.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 example={"1": "test", "3": "nnnn"},
     *                 description="Localized names of the country. Keys represent language IDs."
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="US",
     *                 description="The code representing the country."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function store(CountryRequest $request)
    {

        return $this->countryService->storeCountry($request);
    }

    /**
     * show the country..
     *
     */
    public function show( $id)
    {
        return $this->countryService->show($id);
    }

    /**
     * @OA\Post(
     *     path="/countries/{id}",
     *     operationId="updateCountry",
     *     tags={"Country"},
     *     summary="Update an existing country",
     *     description="Updates a country's code and localized names using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the country to update",
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
     *                 description="Localized names of the country. Keys represent language IDs."
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="US",
     *                 description="The code representing the country."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function update(CountryRequest $request, int $id)
    {
        return $this->countryService->updateCountry($request,$id);
    }

    /**
     * @OA\Delete(
     *     path="/countries/{id}",
     *     operationId="deleteCountry",
     *     tags={"Country"},
     *     summary="Delete a country",
     *     description="Deletes a specific country using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the country to delete",
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
        return $this->countryService->deleteCountry($id);

    }

}
