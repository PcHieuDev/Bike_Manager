<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\BrandRepositoryInterface;

class BrandController extends BaseController
{

    protected $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        parent::__construct($brandRepository);
    }
}
