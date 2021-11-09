<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagController;

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
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    // 顯示產品
    Route::get('/index', [ProductController::class, 'index'])->name('products.index');
        //? 因為在 API route 中已經有 show 方法了，為了避免重複命名所以在 web route 中命名為 showById
    Route::get('/{product_id}/show', [ProductController::class, 'showById'])->name('products.show');
    // 編輯頁面
    Route::get('/{product_id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/{product_id}/update', [ProductController::class, 'update'])->name('products.update');
    //! 測試中(尚未完成)
    Route::delete('/{product_id}/variations/delete', [ProductController::class, 'deleteVariation'])->name('products.deleteVariation');
});

Route::prefix('tags')->middleware(['auth', 'is_admin'])->group(function () {
    // 顯示標籤
    Route::get('/index', [TagController::class, 'index'])->name('tags.index');
    Route::get('/{tag_id}/show', [TagController::class, 'show'])->name('tags.show');
    // 新增標籤
    Route::get('/create', [TagController::class, 'create'])->name('tags.create');
    Route::post('/store', [TagController::class, 'store'])->name('tags.store');
    // 編輯標籤
    Route::get('/{tag_id}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::patch('/{tag_id}/update', [TagController::class, 'update'])->name('tags.update');
});

require __DIR__.'/auth.php';