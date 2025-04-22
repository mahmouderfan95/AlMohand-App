<?php
namespace App\Http\Controllers\Pos\Product;

use App\Http\Controllers\Controller;
use App\Interfaces\ServicesInterfaces\Product\ProductServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     description="Product model",
     *     @OA\Property(property="id", type="integer", example=1, description="Product ID"),
     *     @OA\Property(property="name", type="string", example="Product Name", description="Name of the product"),
     *     @OA\Property(property="price", type="number", format="float", example=99.99, description="Price of the product"),
     *     @OA\Property(property="description", type="string", example="A sample product description", description="Description of the product"),
     *     @OA\Property(property="category_id", type="integer", example=1, description="Category ID of the product"),
     *     @OA\Property(property="brand_id", type="integer", example=2, description="Brand ID of the product"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z", description="Product creation timestamp"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T00:00:00Z", description="Product update timestamp")
     * )
     */
    public function __construct(private ProductServiceInterface $productServiceInterface)
    {
    }

    /**
     * @OA\Get(
     *     path="/products/category/{category_id}/brand/{brand_id}",
     *     operationId="getProducts",
     *     tags={"Product"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="category_id",
     *          in="path",
     *          description="The ID of the category",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="brand_id",
     *          in="path",
     *          description="The ID of the brand",
     *          required=true,
     *          @OA\Schema(type="integer", example=2)
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The page number for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
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
    public function productsByCategoryIdAndBrandId(Request $request, $categoryId, $brandId)
    {
        return $this->productServiceInterface->getProductsByCategoryIdAndBrandId($request, $categoryId, $brandId);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     operationId="getProductById",
     *     tags={"Product"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="The ID of the product",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Product"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        return $this->productServiceInterface->show($id);
    }
}
