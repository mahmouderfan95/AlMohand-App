<?php

namespace App\Http\Controllers\Pos\Category;

use App\Http\Controllers\Controller;
use App\Interfaces\ServicesInterfaces\Category\CategoryServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    public function __construct(private CategoryServiceInterface $categoryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/categories/main",
     *     operationId="getMainCategories",
     *     tags={"Category"},
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
    public function getMainCategories()
    {
        return $this->categoryService->getMainCategories();
    }


    /**
     * @OA\Get(
     *     path="/categories/subs",
     *     operationId="getSubCategories",
     *     tags={"Category"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="parent_id",
     *          in="query",
     *          description="The ID of the parent category",
     *          required=false,
     *          @OA\Schema(type="integer", example=116)
     *      ),
     *      @OA\Parameter(
     *          name="is_brands",
     *          in="query",
     *          description="Flag to filter categories with brands",
     *          required=false,
     *          @OA\Schema(type="integer", example=0)
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
    public function getSubCategories(Request $request)
    {
        return $this->categoryService->getSubCategories($request);
    }
}
