<?php

namespace App\Http\Controllers\Seller\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\AddProductToCartRequest;
use App\Services\Seller\Store\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(public CartService $cartService){}
    public function index()
    {
        return $this->cartService->index();
    }
    public function store(AddProductToCartRequest $request)
    {
        return $this->cartService->store($request->validated());
    }
}
