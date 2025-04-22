<?php
namespace App\Services\Seller\Store;

use App\Http\Resources\Seller\CartResource;
use App\Repositories\Seller\CartRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Facades\DB;

class CartService{
    use ApiResponseAble;
    public function __construct(public CartRepository $cartRepository){}
    public function index()
    {
        try{
            $cart = $this->cartRepository->getCart();
            if(!$cart)
                return $this->ApiErrorResponse([],'cart is empty');
            return $this->ApiSuccessResponse(CartResource::make($cart));
        }catch(\Exception $e){
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }
    public function store($request)
    {
        try{
            DB::beginTransaction();
            #check product qty
            $checkProductQty = $this->cartRepository->checkProductQty($request);
            if($checkProductQty)
                return $checkProductQty;
            $user = auth('sellerApi')->user();
            $cart = $user->carts()->firstOrCreate();
            # Check if the product is already in the cart
            $cartProduct = $this->cartRepository->checkProductExistInCar($cart->id,$request);
            if ($cartProduct) {
                // If the product exists in the cart, update the quantity
                $cartProduct->quantity += $request['quantity'];
                $cartProduct->save();
            } else {
                // Add the product to the cart
                $this->cartRepository->createCartProduct($cart->id,$request);
            }
            DB::commit();
            return $this->ApiSuccessResponse(CartResource::make($cart));

        }catch(\Exception $e){
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }
}
