<?php

use Illuminate\Support\Facades\Route;

//* Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;

//! 'auth:api'  (default)

//* 商品
Route::prefix('v1/products')->group(function () {
    //? 所有商品
    Route::get('/', [ProductController::class, 'index']);
    //? 首頁的產品 (5個)
    Route::get('/indexPage', [ProductController::class, 'indexPageProducts']);
    //? 圖片輪播商品 (10 個)
    Route::get('/carousel', [ProductController::class, 'carousel']);
    //? 商品換頁 (Pagination)
    Route::get('/pagination', [ProductController::class, 'paginate']);
    //? 取得商品的標籤 
    Route::get('/tags/getTags', [ProductController::class, 'productTags']);
    //? 依標籤選擇商品
    Route::get('/tags/{id}', [ProductController::class, 'showByTag']);
    //? 單一商品 
    Route::get('/{id}', [ProductController::class, 'show']);
    //? 搜尋商品
    Route::get('/search/{search}', [ProductController::class, 'search']);
    Route::get('/search/{search}/autocomplete', [ProductController::class, 'searchAutoComplete']);
});

//* 使用者
Route::prefix('v1/user')->group(function () {
    //? 註冊
    Route::post('/register', [UserController::class, 'register']);
    //? 登入
    Route::post('/login', [UserController::class, 'login']);
});

// ------------------------------以下操作必須包含 Token------------------------------ //
Route::prefix('v1/auth/user')->middleware('auth:sanctum')->group(function () {
    // ------------------------------使用者------------------------------ //
        //? 登出
    Route::get('/logout', [UserController::class, 'logout']);
        //? 取得登入的使用者
    Route::get('/getUser', [UserController::class, 'getCurrentUser']);
    // ------------------------------使用者------------------------------ //
    

    // ------------------------------購物車------------------------------ //
    //* 讀取
        //? 使用者的購物車
    Route::get('/cart', [CartController::class, 'show']);
    //* 新增
        //? 加入購物車
    Route::get('/cart/{product_id}/create', [CartController::class, 'create']);
        //? 加入購物車(包含數量)
    Route::post('/cart/{product_id}/create', [CartController::class, 'create']);
    //* 修改
        //? 變更數量
    Route::post('/cart/{product_id}/update', [CartController::class, 'update']);
        //? 數量 + 1 
    Route::get('/cart/{product_id}/increaseByOne', [CartController::class, 'increaseByOne']);
        //? 數量 - 1 
    Route::get('/cart/{product_id}/decreaseByOne', [CartController::class, 'decreaseByOne']);
    //* 刪除
        //? 移除購物車內的商品(單項)
    Route::delete('/cart/{product_id}/delete', [CartController::class, 'destroy']);
        //? 清空購物車
    Route::delete('/cart/deleteAll', [CartController::class, 'destroyAll']);
    // ------------------------------購物車------------------------------ //
    
    
    // ------------------------------訂 單------------------------------ //
    //* 讀取
        //? 給前端的表單資訊
    Route::get('/orders/getTableColumns', [OrderController::class, 'getTableColumns']);
        //? 使用者的所有訂單
    Route::get('/orders', [OrderController::class, 'getAllOrders']);
        //? 使用者的單筆訂單
    Route::get('/orders/{order_id}', [OrderController::class, 'getSingleOrder']);
    //* 新增
        //? 新增訂單
    Route::post('/orders/create', [OrderController::class, 'createOrder']);
    //* 刪除
        //? 刪除訂單
    Route::delete('/orders/{order_id}/delete', [OrderController::class, 'deleteOrder']);
    // ------------------------------訂 單------------------------------ //
    
    
    // ------------------------------通 知------------------------------ //
    //* 讀取
        //? 所有通知
    Route::get('/notifications', [NotificationController::class, 'getAllNotifications']);
        //! 未讀取的通知 (暫時不用)
    Route::get('/notifications/unread', [NotificationController::class, 'getUnReadNotifications']);
    //* 標示為已讀
        //? 單一通知
    Route::post('/notifications/markAsRead', [NotificationController::class, 'markNotification']);
        //? 所有通知
    Route::get('/notifications/markAllAsRead', [NotificationController::class, 'markAllNotifications']);
    //* 刪除
        //? 所有通知
    Route::delete('/notifications/deleteAll', [NotificationController::class, 'deleteAllNotifications']);
    // ------------------------------通 知------------------------------ //
});