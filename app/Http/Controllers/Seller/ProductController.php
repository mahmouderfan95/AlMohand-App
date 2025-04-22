<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\Seller\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService){}
    public function index()
    {
        return $this->productService->index();
    }
    public function search(Request $request)
    {
        return $this->productService->search($request);
    }
}
