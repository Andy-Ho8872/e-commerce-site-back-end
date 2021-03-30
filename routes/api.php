<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

//'auth:api'  (default)


// 使用者
Route::prefix('user')->group(function () {
    // 註冊使用者
    Route::post('/register', [UserController::class, 'register']);
    // 登入使用者
    Route::post('/login', [UserController::class, 'login']);
});
Route::prefix('auth/user')->middleware('auth:sanctum')->group(function () {
    // 登出使用者
    Route::get('/logout', [UserController::class, 'logout']);
    // 取得已登入使用者
    Route::get('/getUser', [UserController::class, 'getCurrentUser']);
});

// 商品
Route::prefix('products')->group(function () {
    // 所有商品
    Route::get('/', [ProductController::class, 'index']);
    // 首頁的產品 (5個)
    Route::get('/indexPage', [ProductController::class, 'indexPageProducts']);
    // 圖片輪播商品 (10 個)
    Route::get('/carousel', [ProductController::class, 'carousel']);
    // 商品換頁 (Pagination)
    Route::get('/pagination', [ProductController::class, 'paginate']);
    // 單一商品 
    Route::get('/{id}', [ProductController::class, 'show']);
    // 依標籤選擇
    Route::get('/tag/{id}', [ProductController::class, 'showByTag']);
    // 搜尋商品
    Route::get('/search/{search}', [ProductController::class, 'search']);
});

// 購物車 (要有 Token 才能進行此處操作)
Route::prefix('auth/user/cart')->middleware('auth:sanctum')->group(function () {
    // 使用者的購物車
    Route::get('/', [CartController::class, 'show']);
    // 商品加入購物車
    Route::get('/{product_id}/create', [CartController::class, 'create']);
    Route::post('/{product_id}/create', [CartController::class, 'create']);
    // 更新購物車
    Route::post('/{product_id}/update', [CartController::class, 'update']);
    // 數量 + 1 
    Route::get('/{product_id}/increseByOne', [CartController::class, 'increseByOne']);
    // 數量 - 1 
    Route::get('/{product_id}/decreseByOne', [CartController::class, 'decreseByOne']);
    // 移除購物車
    Route::delete('/{product_id}/delete', [CartController::class, 'destroy']);
    // 清空購物車
    Route::delete('/deleteAll', [CartController::class, 'destroyAll']);
});

// 訂單
Route::prefix('auth/user/order')->middleware('auth:sanctum')->group(function () {
    // 給前端的表單資訊
    Route::get('/getFormData', [OrderController::class, 'getFormData']);
    // 使用者的所有訂單
    Route::get('/', [OrderController::class, 'getAllOrders']);
    // 使用者的單筆訂單
    Route::get('/{order_id}', [OrderController::class, 'getSingleOrder']);
    // 新增訂單
    Route::post('/create', [OrderController::class, 'createOrder']);
    // 刪除訂單
    Route::delete('/{order_id}/delete', [OrderController::class, 'deleteOrder']);
});