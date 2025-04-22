<?php
namespace App\Repositories\Seller;

use App\Models\Brand\Brand;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;

class BrandRepository{
    public function getAllBrands($request)
    {
        $searchTerm = $request->input('name'); // Search by brand name
        $categoryFilter = $request->input('category_filter'); // Filter by category ID
        $perPage = $request->input('per_page', 10); // Number of items per page, default to 10

        $brands = Brand::with(['category_brands', 'translations','images'])
            // Filter by category if `category_filter` is present in the request
            ->when($categoryFilter, function ($query, $categoryFilter) {
                $query->whereHas('category_brands', function ($q) use ($categoryFilter) {
                    $q->where('id', $categoryFilter);
                });
            })
            // Search by name if `name` is present in the request
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->whereHas('translations', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
            })
            // Paginate the results
            ->paginate($perPage);

        return $brands;
    }
    public function getProductIds($categoryId)
    {
        // Fetch product IDs from the pivot table
        $productIds = ProductCategory::where('category_id', $categoryId)->pluck('product_id');
        return $productIds;
    }
    public function getProducts($productIds)
    {
        // Fetch products using the retrieved IDs
        $products = Product::whereIn('id', $productIds)->get();
        return $products;
    }
}
