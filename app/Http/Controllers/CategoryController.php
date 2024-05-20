<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

use App\Models\Category;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        parent::__construct($categoryRepository);
    }


}