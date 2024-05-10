<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function getAll()
    {

        $brands = Brand::all();
        return response()->json(
            [
                'contents' => $brands,
                'code' => '200'

            ]);

    }
}
