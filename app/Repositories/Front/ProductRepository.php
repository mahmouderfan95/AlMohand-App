<?php
namespace App\Repositories\Front;

use App\Http\Resources\Seller\ProductResource;
use App\Models\Product\Product;
use App\Traits\ApiResponseAble;

class ProductRepository
{
    use ApiResponseAble;
    public function getAllProducts($perPage = 15)
     {
        try{
            $products = $this->getModel()::query()
            ->with(['brand','productCategory'])
            ->orderByDesc('id')
            ->paginate($perPage);
            if($products->count() > 0)
                return $this->ApiSuccessResponse(ProductResource::collection($products));
            return $this->ApiErrorResponse([],trans('seller.products.not_found'));
        }catch(\Exception $e){
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }
    public function search($request)
    {
        try{
            $products = $this->getModel()::where('name', 'LIKE', "%{$request['search']}%")->paginate(10);
            if($products->count() > 0){
                return $this->ApiSuccessResponse(ProductResource::collection($products));
            }
            return $this->ApiErrorResponse([],trans('seller.products.not_found'));
        }catch(\Exception $e){
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }
    private function getModelById($id)
    {
        return $this->getModel()::find($id);
    }
    private function getModel()
    {
        return Product::class;
    }
}
