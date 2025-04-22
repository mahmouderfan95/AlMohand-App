<?php

namespace App\Interfaces\ServicesInterfaces\Product;

use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface ProductServiceInterface extends BaseServiceInterface
{
    public function search();

    public function getProductsByCategoryIdAndBrandId(Request $request, $categoryId, $brandId);
}
