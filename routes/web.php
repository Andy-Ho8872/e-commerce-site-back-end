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
    Route::post('/create', [ProductController::class, 'store'])->name('products.store');
    // 所有產品頁面
    Route::get('/showAll', [ProductController::class, 'products'])->name('products.index');
    // 單一產品頁面
    Route::get('/show/{id}', [ProductController::class, 'showById'])->name('products.show');
    // 編輯頁面
    Route::get('/edit/{id}', [ProductController::class, 'editPage'])->name('products.edit');
    Route::patch('/edit/{id}', [ProductController::class, 'edit'])->name('products.update');
});

require __DIR__.'/auth.php';