<?php

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;



/*
|--------------------------------------------------------------------------
| API Routes                                                                
|--------------------------------------------------------------------------
*/

//'auth:api'  (default)
// 需要取得 Token 後才能進行對該 Route 進行存取或修改
Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
    return $request->user();
});

// 127.0.0.1:8000/api/auth/user


// 使用者
// Route::post('auth/user/register', [UserController::class, 'login'])->name('user.login');

Route::prefix('auth/user')->group(function () {
    // 註冊使用者
    Route::post('/register', [UserController::class, 'register'])->name('user.register');
    // 登入使用者
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
    // 登出使用者
    Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum'); 
    // 取得已登入使用者
    Route::get('/{id}', [UserController::class, 'getCurrentUser']);
    // 所有使用者
    Route::get('/', [UserController::class, 'index']);
});

// 商品
Route::prefix('products')->group(function () {
    // 所有商品
    Route::get('/', [ProductController::class, 'index']);
    // 單一商品 
    Route::get('/{id}', [ProductController::class, 'show']); 
    // 依標籤選擇
    Route::get('/tag/{id}', [ProductController::class, 'showByTag']);
    // 搜尋商品
    Route::get('/search/{title}', [ProductController::class, 'search']); 
});


// 購物車
Route::prefix('auth/user')->group(function () {
    // 特定使用者的購物車
    Route::get('/{id}/cart', [CartController::class, 'show']);
        // 商品加入購物車
    Route::post('/{id}/cart/{product_id}/create', [CartController::class, 'store']);
        // 更新購物車
    Route::post('/{id}/cart/{product_id}/update', [CartController::class, 'update']);
    Route::post('/{id}/cart/{product_id}/increseByOne', [CartController::class, 'increseByOne']);
    Route::post('/{id}/cart/{product_id}/decreseByOne', [CartController::class, 'decreseByOne']);
        // 移除購物車
    // Route::delete('/{id}/cart/{product_id}/delete', [CartController::class, 'destroy']);
    Route::delete('/{userId}/cart/{id}/delete', [CartController::class, 'destroy']); // 測試
        // 清空購物車
    Route::delete('/{id}/cart/deleteAll', [CartController::class, 'destroyAll']);
});









// Create a product (POST) /api/products


// Get single products (GET) /api/products/{id}


// Update single product (PUT/PATCH) /api/products/{id}


// Delete product (DELETE) /api/products/{id}
