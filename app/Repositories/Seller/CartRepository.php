<?php
namespace App\Repositories\Seller;

use App\Models\Cart\Cart;
use App\Models\Cart\CartProduct;
use App\Models\Product\Product;
use App\Traits\ApiResponseAble;

class CartRepository
{
    use ApiResponseAble;
    public function checkProductExistInCar($cartId,$data)
    {
        $cartProduct = CartProduct::where('cart_id', $cartId)
                ->where('product_id', $data['product_id'])
                ->first();
        return $cartProduct;
    }
    public function createCartProduct($cartId,$data)
    {
        return CartProduct::create([
            'cart_id' => $cartId,
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'category_id' => $data['category_id']
        ]);
    }
    public function checkProductQty($data)
    {
        $product = Product::find($data['product_id']);
        // dd($product);
        // Check if the product's stock is zero
        if ($product->quantity <= 0) {
            return $this->ApiErrorResponse([],'This product is currently out of stock.');
        }
        // Check if the requested quantity is greater than available stock
        if ($data['quantity'] > $product->quantity) {
            return $this->ApiErrorResponse([],'Requested quantity exceeds available stock.');
        }
    }
    public function getCart()
    {
        $user = auth('sellerApi')->user();
        $cart = $user->carts()->with('cartProducts.product')->first();
        return $cart;
    }
    private function getModel()
    {
        return Cart::class;
    }
    private function getModelById($id)
    {
        return $this->getModel()::find($id);
    }
}
