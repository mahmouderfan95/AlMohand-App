<?php

namespace App\Http\Controllers\Pos\Brand;

use App\Http\Controllers\Controller;
use App\Interfaces\ServicesInterfaces\Brand\BrandServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class BrandController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="Brand",
     *     type="object",
     *     title="Brand",
     *     description="Brand model",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Brand Name"),
     *     @OA\Property(property="category_id", type="integer", example=123),
     *     @OA\Property(property="logo", type="string", example="https://example.com/logo.png")
     * )
     */
    public function __construct(private BrandServiceInterface $brandService)
    {
    }

    /**
     * @OA\Get(
     *     path="/brands/category/{categoryId}",
     *     operationId="getBrandsByCategoryId",
     *     tags={"Brand"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="categoryId",
     *          in="path",
     *          description="The ID of the category",
     *          required=true,
     *          @OA\Schema(type="integer", example=123)
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="List of brands for the given category",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Brands retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Brand")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function getBrandsByCategoryId(Request $request, $categoryId)
    {
        return $this->brandService->getCategoryBrands($request, $categoryId);
    }
    /**
     * @OA\Get(
     *     path="/brands/show/{id}",
     *     operationId="showBrandDetails",
     *     tags={"Brand"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="The ID of the brand",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Brand details retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Brand")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Brand not found")
     *         )
     *     )
     * )
     */
    public function show_details(Request $request, $id)
    {
        return $this->brandService->show_details($request, $id);
    }
}
