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

// 後台首頁
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 上架頁面
Route::get('/products/create', [ProductController::class, 'getTags'])->name('products.create');
Route::post('/products/create', [ProductController::class, 'store']);

// 所有產品頁面
Route::get('products/showAll', [ProductController::class, 'products'])->name('products.index');
// 單一產品頁面
Route::get('products/show/{id}', [ProductController::class, 'showById'])->name('products.show');
    // 編輯頁面
Route::get('products/edit/{id}', [ProductController::class, 'editPage'])->name('products.edit');
Route::patch('products/edit/{id}', [ProductController::class, 'edit']);




