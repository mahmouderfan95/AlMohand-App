<?php

namespace App\Services\Seller\Store;

use App\Http\Resources\Seller\BrandsResource;
use App\Http\Resources\Seller\CategoriesResource;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Category\CategoryBrand;
use App\Repositories\Seller\BrandRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Collection;

class BrandService
{
    use ApiResponseAble;

    public function __construct(protected BrandRepository $brandRepository)
    {
    }

    /**
     * Retrieve all brands based on the request.
     */
    public function index($request)
    {
        return $this->handleApiResponse(function () use ($request) {
            $brands = $this->brandRepository->getAllBrands($request);
            return $this->prepareResponse($brands, BrandsResource::class);
        });
    }

    /**
     * Retrieve categories associated with a brand.
     */
    public function getCategories($brandId)
    {
        return $this->handleApiResponse(function () use ($brandId) {
            $categories = CategoryBrand::where('brand_id', $brandId)->get();
            return $this->prepareResponse($categories, CategoriesResource::class);
        });
    }

    /**
     * Retrieve products associated with a category.
     */
    public function getProducts($categoryId)
    {
        return $this->handleApiResponse(function () use ($categoryId) {
            $productIds = $this->brandRepository->getProductIds($categoryId);
            $products = $this->brandRepository->getProducts($productIds);
            return $this->prepareResponse($products, ProductResource::class);
        });
    }

    /**
     * Handle API responses with common error handling.
     */
    protected function handleApiResponse(callable $callback)
    {
        try {
            return $callback();
        } catch (\Exception $ex) {
            return $this->ApiErrorResponse([], trans('admin.general_error') . ': ' . $ex->getMessage());
        }
    }

    /**
     * Prepare the response with resource transformation or an empty result message.
     */
    protected function prepareResponse(Collection $items, string $resourceClass)
    {
        if ($items->isNotEmpty()) {
            return $this->ApiSuccessResponse($resourceClass::collection($items));
        }

        return $this->ApiErrorResponse([], trans('messages.data_not_found'));
    }
}
