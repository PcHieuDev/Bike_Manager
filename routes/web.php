<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can Register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product-management', function () {
    return view('welcome');
});

Route::get('/product-management/{id}', function ($id) {
    return view('welcome');
});


Route::post('/addproduct', function () {
    return view('welcome');
});

Route::get('/product/details/{id}', function ($id) {
    return view('welcome');
});

Route::get('/details', function () {
    return view('welcome');
});

Route::get('/product/edit/{id}', function ($id) {
    return view('welcome');
});

Route::get('/Login', function () {
    return view('welcome');
});

Route::get('/Register', function () {
    return view('welcome');
});