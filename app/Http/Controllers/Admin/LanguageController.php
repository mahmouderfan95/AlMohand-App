<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Services\Admin\LanguageService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Prettus\Validator\Exceptions\ValidatorException;


class LanguageController extends Controller
{
    public $languageService;

    /**
     * Language  Constructor.
     */
    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }


    /**
     * @OA\Get(
     *     path="/languages",
     *     operationId="getLanguages",
     *     tags={"Languages"},
     *     summary="Retrieve a list of supported languages",
     *     description="Fetches a list of all supported languages.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *          response=401,
     *          ref="#/components/responses/Unauthenticated"
     *      )
     * )
     */
    public function index(Request $request)
    {
        return $this->languageService->getAllLanguages($request);
    }


    /**
     * @OA\Post(
     *     path="/languages",
     *     operationId="storeLanguage",
     *     tags={"Languages"},
     *     summary="Store a new language",
     *     description="Creates a new language with the provided details.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="English"),
     *             @OA\Property(property="code", type="string", example="EN"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="sort_order", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function store(LanguageRequest $request)
    {

        return $this->languageService->storeLanguage($request);
    }

    /**
     * @OA\Get(
     *     path="/languages/{id}",
     *     operationId="showLanguageDetails",
     *     tags={"Languages"},
     *     summary="Show language details",
     *     description="Fetch details of a specific language by ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the language",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function show(int $id)
    {
        return $this->languageService->show($id);
    }

    /**
     * @OA\Post(
     *     path="/languages/{id}/update",
     *     operationId="updateLanguage",
     *     tags={"Languages"},
     *     summary="Update an existing language",
     *     description="Updates a language's details using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the language to update",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="English"),
     *             @OA\Property(property="code", type="string", example="EN"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="sort_order", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function update(LanguageRequest $request, int $id)
    {
        return $this->languageService->updateLanguage($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/languages/{id}",
     *     operationId="deleteLanguage",
     *     tags={"Languages"},
     *     summary="Delete a language",
     *     description="Deletes a specific language using its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the language to delete",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function destroy(int $id)
    {
        return $this->languageService->deleteLanguage($id);

    }

}
