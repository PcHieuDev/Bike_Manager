<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends Controller
{
    public function getAll()
    {

        $brands = Brand::all();
        return response()->json(
            [
                'contents' => $brands,
                'code' => Response::HTTP_OK

            ]);

    }
}
