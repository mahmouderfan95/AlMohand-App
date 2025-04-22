<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequests\ProductMultiDeleteRequest;
use App\Http\Requests\Admin\ProductRequests\ProductRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public $productService;

    /**
     * Product  Constructor.
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }



    /**
     * @OA\Get(
     *     path="/products",
     *     operationId="getProducts",
     *     tags={"Product"},
     *     summary="Retrieve a list of products",
     *     description="Fetches a list of all available products, with optional sorting, filtering, and pagination.",
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
     *         description="Search term to filter products by name",
     *         example="laptop"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Laptop"),
     *                 @OA\Property(property="price", type="number", format="float", example=999.99),
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
        return $this->productService->getAllProducts($request);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     operationId="storeProduct",
     *     tags={"Product"},
     *     summary="Create a new product",
     *     description="Creates a new product with the provided details.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data that needs to be stored",
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="price", type="number", format="float", example=999.99),
     *             @OA\Property(property="description", type="string", nullable=true, example="High-end gaming laptop")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="price", type="number", format="float", example=999.99),
     *             @OA\Property(property="description", type="string", example="High-end gaming laptop")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function store(ProductRequest $request)
    {
        return $this->productService->storeProduct($request);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     operationId="getProductById",
     *     tags={"Product"},
     *     summary="Retrieve a single product",
     *     description="Fetches details of a specific product by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="price", type="number", format="float", example=999.99),
     *             @OA\Property(property="description", type="string", example="High-end gaming laptop")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function show(int $id)
    {
        return $this->productService->show($id);
    }

    /**
     * @OA\post(
     *     path="/products/{id}",
     *     operationId="updateProduct",
     *     tags={"Product"},
     *     summary="Update an existing product",
     *     description="Updates details of a specific product by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated product data",
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Updated Laptop"),
     *             @OA\Property(property="price", type="number", format="float", example=899.99),
     *             @OA\Property(property="description", type="string", nullable=true, example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Laptop"),
     *             @OA\Property(property="price", type="number", format="float", example=899.99),
     *             @OA\Property(property="description", type="string", example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function update(ProductRequest $request, int $id)
    {
        return $this->productService->updateProduct($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     operationId="deleteProduct",
     *     tags={"Product"},
     *     summary="Delete a product",
     *     description="Deletes a product by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        return $this->productService->deleteProduct($id);
    }

    /**
     * @OA\Get(
     *     path="/products/get-brand-products/{brand_id}",
     *     tags={"Products"},
     *     summary="Get all products for a brand",
     *     @OA\Parameter(
     *         name="brand_id",
     *         in="path",
     *         description="ID of the brand",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Brand not found"),
     * )
     */
    public function get_brand_products(int $brand_id)
    {
        return $this->productService->get_brand_products($brand_id);
    }

    /**
     * @OA\Post(
     *     path="/product/multiDelete",
     *     tags={"Products"},
     *     summary="Delete multiple products",
     *     @OA\Response(response=200, description="Products deleted successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function multiDelete(ProductMultiDeleteRequest $request)
    {
        return $this->productService->multiDelete($request);
    }

    // Add Swagger for the rest of your methods similarly...

    /**
     * @OA\Post(
     *     path="/product/applyPriceAll",
     *     tags={"Products"},
     *     summary="Apply price changes to all products",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="price", type="number", description="New price to apply")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Prices updated successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function applyPriceAll(Request $request)
    {
        return $this->productService->applyPriceAll($request);
    }

    /**
     * @OA\Post(
     *     path="/product/serials",
     *     tags={"Products"},
     *     summary="Store product serials",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="serials", type="array", @OA\Items(type="string"), description="Array of serials")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Serials stored successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function serials(Request $request)
    {
        return $this->productService->serials($request);
    }

    /**
     * @OA\Post(
     *     path="/product/applyPriceAllGroups",
     *     tags={"Products"},
     *     summary="Apply price changes to all product groups",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="price", type="number", description="New price to apply")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Prices updated successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function applyPriceAllGroups(Request $request)
    {
        return $this->productService->applyPriceAllGroups($request);
    }

    /**
     * @OA\Get(
     *     path="/products/prices",
     *     tags={"Products"},
     *     summary="Get prices of all products",
     *     @OA\Response(response=200, description="List of product prices"),
     *     @OA\Response(response=404, description="No products found")
     * )
     */
    public function prices(Request $request)
    {
        return $this->productService->prices($request);
    }

    /**
     * @OA\post(
     *     path="/product/changeStatus/{id}",
     *     tags={"Products"},
     *     summary="Change the status of a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="New status of the product")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Product status updated successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function changeStatus(Request $request, int $id)
    {
        return $this->productService->changeStatus($request, $id);
    }

    /**
     * @OA\post(
     *     path="/product/delete_image_product/{id}",
     *     tags={"Products"},
     *     summary="Delete a product's image",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Image deleted successfully"),
     *     @OA\Response(response=404, description="Product or image not found")
     * )
     */
    public function delete_image_product(int $id)
    {
        return $this->productService->delete_image_product($id);
    }



}
