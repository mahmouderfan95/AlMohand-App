<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CategoryRequest;
use App\Services\Admin\CategoryService;

class CategoryController extends Controller
{
    public $categoryService;

    /**
     * Category  Constructor.
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    /**
     * @OA\Get(
     *     path="/categories",
     *     operationId="getCategories",
     *     tags={"Category"},
     *     summary="Retrieve a list of categories",
     *     description="Fetches a list of all available categories, with optional sorting, filtering, and pagination.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         required=false,
     *         description="Field to sort the results by (default: created_at)",
     *         example="name"
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         required=false,
     *         description="Direction to sort the results (default: desc)",
     *         example="desc"
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search term to filter categories by name",
     *         example="electronics"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Electronics"),
     *                 @OA\Property(property="web", type="string", example="Web Description"),
     *                 @OA\Property(property="mobile", type="string", example="Mobile Description"),
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
        return $this->categoryService->getAllCategories($request);
    }


    /**
     * All Cats
     */
    public function getAllCategoriesForm(Request $request)
    {
        return $this->categoryService->getAllCategoriesForm($request);
    }


    /**
     * @OA\Post(
     *     path="/categories",
     *     operationId="storeCategory",
     *     tags={"Category"},
     *     summary="Create a new category",
     *     description="Creates a new category with the provided details.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data that needs to be stored",
     *         @OA\JsonContent(
     *             required={"name", "meta_title"},
     *             @OA\Property(property="name", type="array", @OA\Items(type="string"), example={"en": "Electronics", "ar": "إلكترونيات"}),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=0),
     *             @OA\Property(property="image", type="string", format="binary", nullable=true, description="Category image (max 1MB)"),
     *             @OA\Property(property="meta_title", type="array", @OA\Items(type="string"), example={"en": "Electronic Devices", "ar": "أجهزة إلكترونية"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="array", @OA\Items(type="string"), example={"en": "Electronics", "ar": "إلكترونيات"}),
     *             @OA\Property(property="parent_id", type="integer", example=0),
     *             @OA\Property(property="image", type="string", example="category.jpg"),
     *             @OA\Property(property="meta_title", type="array", @OA\Items(type="string"), example={"en": "Electronic Devices", "ar": "أجهزة إلكترونية"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function store(CategoryRequest $request)
    {
        return $this->categoryService->storeCategory($request);
    }


    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     operationId="getCategoryById",
     *     tags={"Category"},
     *     summary="Retrieve a single category",
     *     description="Fetches details of a specific category by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
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
        return $this->categoryService->show($id);
    }



    /**
     * @OA\post(
     *     path="/categories/{id}",
     *     operationId="updateCategory",
     *     tags={"Category"},
     *     summary="Update an existing category",
     *     description="Updates details of a specific category by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
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
    public function update(CategoryRequest $request, int $id)
    {
        return $this->categoryService->updateCategory($request, $id);
    }




    /**
     * Update the category..
     *
     */
    public function update_status(Request $request, int $id)
    {
        return $this->categoryService->update_status($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     operationId="deleteCategory",
     *     tags={"Category"},
     *     summary="Delete a category",
     *     description="Deletes a category by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
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
        return $this->categoryService->deleteCategory($id);

    }

    /**
     * @OA\post(
     *     path="/categories/destroy-selected",
     *     operationId="destroySelectedCategories",
     *     tags={"Category"},
     *     summary="Delete selected categories",
     *     description="Deletes multiple categories by their IDs.",
     *     @OA\Parameter(
     *         name="category_ids",
     *         in="query",
     *         required=true,
     *         description="Comma-separated list of Category IDs to be deleted.",
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
    {
        return $this->categoryService->destroy_selected($request);
    }


    /**
     *
     * trash Category
     *
     */
    public function trash()
    {
        return $this->categoryService->trash();

    }

    /**
     *
     * trash Category
     *
     */
    public function restore(int $id)
    {
        return $this->categoryService->restore($id);

    }

}
