<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

use App\Models\Category;
use Illuminate\Http\Response;

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
                'code' => Response::HTTP_OK
            ]);
    }
}