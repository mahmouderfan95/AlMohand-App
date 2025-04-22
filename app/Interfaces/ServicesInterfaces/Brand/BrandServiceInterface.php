<?php

namespace App\Interfaces\ServicesInterfaces\Brand;

use App\Interfaces\ServicesInterfaces\BaseServiceInterface;

interface BrandServiceInterface extends BaseServiceInterface
{
    public function getCategoryBrands($request, $category_id);

    public function changeStatus($request, $id);
}
