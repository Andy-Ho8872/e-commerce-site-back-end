<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
// 後臺主控面板(一般訪客)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
// 後台主控面板(管理者)
Route::prefix('products')->middleware(['auth', 'is_admin'])->group(function () {
    // 上架頁面
    Route::get('/create', [ProductController::class, 'getTags'])->name('products.create');
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    // 所有產品頁面
    Route::get('/showAll', [ProductController::class, 'products'])->name('products.index');
    // 單一產品頁面
    Route::get('/{product_id}/show', [ProductController::class, 'showById'])->name('products.show');
    // 編輯頁面
    Route::get('/{product_id}/edit', [ProductController::class, 'editPage'])->name('products.edit');
    Route::patch('/{product_id}/update', [ProductController::class, 'edit'])->name('products.update');
});

Route::prefix('tags')->middleware(['auth', 'is_admin'])->group(function () {
    // 顯示標籤
    Route::get('/showAll', [ProductController::class, 'example'])->name('tags.index');
    // 新增標籤
    Route::post('/store', [ProductController::class, 'example'])->name('tags.store');
    // 編輯標籤
    Route::patch('/{tag_id}/edit', [ProductController::class, 'example'])->name('tags.store');
});
require __DIR__.'/auth.php';