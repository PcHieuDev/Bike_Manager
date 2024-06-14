<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::group(['middleware' => 'api.Login'], function () {
        //product manager api
        Route::delete('/delete_products/{id}', [ProductController::class, 'delete']);
        Route::get('/products', [ProductController::class, 'getData']);
        Route::post('/saveProduct', [ProductController::class, 'createProduct']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
    });

    Route::get('/search', [ProductController::class, 'search']);
    // brand api
    Route::get('/brands', [BrandController::class, 'getAll']);
    Route::get('/brands/{brandId}/products', [ProductController::class, 'getByBrand']);
    // category api
    Route::get('/categories', [CategoryController::class, 'getAll']);

    // product api
    Route::get('/', [ProductController::class, 'getProducts']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/product', [ProductController::class, 'getData']);
    //Authen api
    Route::post('/Register', [AuthController::class, 'register']);
    Route::post('/Login', [AuthController::class, 'login']);
});
