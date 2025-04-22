<?php
namespace App\Services\Seller\Store;

use App\Http\Resources\Seller\ProductResource;
use App\Models\favorite;
use App\Traits\ApiResponseAble;

class FavService
{
    use ApiResponseAble;
    public function store($data,$favoritable)
    {
        $existingFavorite = favorite::where([
            ['product_id', $data['product_id']],
            ['favoritable_type', get_class($favoritable)],
            ['favoritable_id', $favoritable->id],
        ])->first();
        if ($existingFavorite) {
            return $this->ApiErrorResponse([],'Product is already in favorites');
        }
        // Add the product to the favoritable's favorites
        $favoritable->favorites()->create(['product_id' => $data['product_id']]);
        return $this->ApiSuccessResponse([],'Product added to favorites successfully');
    }
    public function getProducts()
    {
        $favoritable = auth('sellerApi')->user();
        // Load the favorites along with related products
        $favorites = $favoritable->favorites()->with('product')->get();
        if($favoritable->count() > 0)
            return $this->ApiSuccessResponse(ProductResource::collection($favorites->pluck('product')));
        return $this->ApiErrorResponse([],'data not found');
    }
}
