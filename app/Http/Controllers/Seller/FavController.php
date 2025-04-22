<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\AddProductToFavRequest;
use App\Models\favorite;
use App\Services\Seller\Store\FavService;
use Illuminate\Http\Request;

class FavController extends Controller
{
    public function __construct(public FavService $favService){}
    public function store(AddProductToFavRequest $request)
    {
        $favoritable = auth('sellerApi')->user();
        // Check if the product is already favorited
        return $this->favService->store($request->validated(),$favoritable);
    }
    public function getProducts()
    {
        return $this->favService->getProducts();
    }
}
