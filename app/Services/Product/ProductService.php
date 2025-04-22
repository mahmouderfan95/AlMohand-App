<?php

namespace App\Services\Product;

use App\DTO\BaseDTO;

use App\Http\Resources\Pos\BrandResources\BrandResource;
use App\Http\Resources\Pos\CategoryResources\CategoryResource as MerchantCategoryResource;
use App\Http\Resources\Pos\ProductResources\ProductResource;
use App\Interfaces\ServicesInterfaces\Product\ProductServiceInterface;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductService extends BaseService implements ProductServiceInterface
{

    public function __construct(
        private readonly ProductRepository  $productRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly BrandRepository    $brandRepository,
    )
    {

    }

    public function index(array $filter): mixed
    {
        // TODO: Implement index() method.
    }

    public function show($id): mixed
    {
        try {
            DB::beginTransaction();
            $product = $this->productRepository->show($id);
            if (!$product)
                return $this->notFoundResponse();
            DB::commit();
            return $this->showResponse(new ProductResource($product));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function store(BaseDTO $data): mixed
    {
        // TODO: Implement store() method.
    }

    public function update($id, BaseDTO $data): mixed
    {
        // TODO: Implement update() method.
    }

    public function delete($id): mixed
    {
        // TODO: Implement delete() method.
    }

    public function getTrashed()
    {
        // TODO: Implement getTrashed() method.
    }

    public function restore($id)
    {
        // TODO: Implement restore() method.
    }

    public function bulkDelete(array $ids = [])
    {
        // TODO: Implement bulkDelete() method.
    }

    public function search()
    {
        // TODO: Implement search() method.
    }

    public function getProductsByCategoryIdAndBrandId($request, $categoryId, $brandId)
    {
        try {
            DB::beginTransaction();
            $data = [];
            $category = $this->categoryRepository->show($categoryId);
            $brand = $this->brandRepository->show($brandId);
            if (!$category || !$brand) {
                return $this->ApiErrorResponse(null, __('admin.general_error'));
            }
            $products = $this->productRepository->productsByCategoryIdAndBrandId($request, $category->id, $brand->id ,$brand->has_subs);
            $data['brand'] = new BrandResource($brand);
            $data['category'] = new MerchantCategoryResource($category);
            // $data['products'] = ProductDetailsResource::collection($products)->resource;
            $data['products'] = ProductResource::collection($products)->resource;

            DB::commit();
            return $this->showResponse($data);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }
}
