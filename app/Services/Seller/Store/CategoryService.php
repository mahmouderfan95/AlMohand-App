<?php
namespace App\Services\Seller\Store;

use App\Repositories\Seller\CategoryRepository;
use App\Traits\ApiResponseAble;

class CategoryService{
    use ApiResponseAble;
    public function __construct(public CategoryRepository $categoryRepository){}
    public function index()
    {
        $categories = $this->categoryRepository->getAllCategories();
        if($categories->count() > 0)
            return $this->ApiSuccessResponse($categories);
        return $this->ApiErrorResponse([],'data not found');
    }
}
