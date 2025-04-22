<?php
namespace App\Repositories\Seller;

use App\Models\Category\Category;

class CategoryRepository
{
    public function getAllCategories()
    {
        $categories = $this->getModel()::get(['id','name']);
        return $categories;
    }
    private function getModelById($id)
    {
        return $this->getModel()::find($id);
    }
    private function getModel()
    {
        return Category::class;
    }
}
