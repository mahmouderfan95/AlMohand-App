<?php
namespace App\Services\Seller;

use App\Repositories\Front\ProductRepository;

class ProductService{
    public function __construct(public ProductRepository $productRepository){}
    public function index()
    {
        return $this->productRepository->getAllProducts();
    }
    public function search($request)
    {
        return $this->productRepository->search($request);
    }
}
