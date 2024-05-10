<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

use App\Models\Category;
class CategoryController extends Controller
{
    // protected $categoryRepository;

    // public function __construct(CategoryRepositoryInterface $categoryRepository)
    // {
    //     $this->categoryRepository = $categoryRepository;
    // }

    public function getAll()
    {
        $category = Category::all();
        return response()->json(
            [
                'contents' => $category,
                'code' => '200'
            ]);
    }
}