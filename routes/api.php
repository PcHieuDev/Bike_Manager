<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Models\User;
use App\Http\Controllers\RegisterController ;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('products', [ProductController::class, 'getData']);

// routes/api.php
Route::get('/search', [ProductController::class, 'search']);

Route::post('/save', [ProductController::class, 'saveProduct']);

Route::post('/saveProduct', [ProductController::class, 'saveProduct']);

Route::get('/brands', [BrandController::class, 'getAll']);

Route::get('/categories', [CategoryController::class, 'getAll']);

Route::get('/', [ProductController::class, 'getProducts']);



Route::put('/products/{id}', [ProductController::class, 'update']);


Route::get('/products/{id}', [ProductController::class, 'show']);


Route::delete('/delete_products/{id}', [App\Http\Controllers\ProductController::class, 'delete']);


