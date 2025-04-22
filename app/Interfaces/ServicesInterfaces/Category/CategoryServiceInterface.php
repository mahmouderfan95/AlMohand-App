<?php

namespace App\Interfaces\ServicesInterfaces\Category;

use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface CategoryServiceInterface extends BaseServiceInterface
{
    public function updateStatus(Request $request, int $category_id);

    public function getMainCategories();

    public function getSubCategories(Request $request);
}
