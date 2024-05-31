<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Models\User;
use App\Http\Controllers\AuthController;

Route::get('/search', [ProductController::class, 'search']);
Route::get('/brands', [BrandController::class, 'getAll']);
Route::get('/categories', [CategoryController::class, 'getAll']);
Route::get('/', [ProductController::class, 'getProducts']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('productsFree', [ProductController::class, 'getData']);
Route::post('/Register', [AuthController::class, 'register']);
Route::post('/Login', [AuthController::class, 'login']);
Route::get('/products/brand/{brandId}', [ProductController::class, 'getProductsByBrandId']);

Route::get('/brands/{brandId}/products', [ProductController::class, 'getByBrand']);
Route::get('/categories/{categoryId}/products', [ProductController::class, 'getByCategory']);
Route::group(['middleware' => 'api.Login'], function () {
    Route::delete('/delete_products/{id}', [ProductController::class, 'delete']);
    Route::get('products', [ProductController::class, 'getData']);
    Route::post('/saveProduct', [ProductController::class, 'saveProduct']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::put('/products/{id}', [ProductController::class, 'updateProduct']);

});
