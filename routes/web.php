<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products/create', function () {
    return view('products.create');
});
// 所有產品頁面
Route::get('products/showAll', [ProductController::class, 'products']);
// 單一產品頁面
Route::get('products/show/{id}', [ProductController::class, 'showById']);
    // 編輯頁面
Route::get('products/edit/{id}', [ProductController::class, 'editPage']);
Route::patch('products/edit/{id}', [ProductController::class, 'edit']);



Route::get('/products/create', [ProductController::class, 'getTags']);
Route::post('/products/create', [ProductController::class, 'store']);
