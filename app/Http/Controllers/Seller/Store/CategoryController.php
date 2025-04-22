<?php

namespace App\Http\Controllers\Seller\Store;

use App\Http\Controllers\Controller;
use App\Services\Seller\Store\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService){}
    public function index()
    {
        return $this->categoryService->index();
    }
}
