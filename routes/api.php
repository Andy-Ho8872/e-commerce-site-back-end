<?php

use Illuminate\Support\Facades\Route;

//* Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;

//! 'auth:api'  (default)
//* 管理者操作
Route::prefix('v1/admin/product')->middleware(['auth:sanctum', 'is_admin'])->group(function() {
    //* 產品 
    Route::get('/', [AdminController::class, 'showProducts']);
    Route::get('/{product_id}', [AdminController::class, 'showProduct']);
    Route::post('/store', [AdminController::class, 'storeProduct']);
    Route::patch('/{product_id}/update', [AdminController::class, 'updateProduct']);
    Route::post('/{product_id}/variation/create', [AdminController::class, 'createProductVariation']);
    Route::delete('/{product_id}/variation/{variation_id}/delete', [AdminController::class, 'deleteProductVariation']);
    Route::patch('/{product_id}/variation/{variation_id}/update', [AdminController::class, 'updateProductVariationOption']);
    //* 產品的標籤
    Route::get('/tag/getTags', [AdminController::class, 'showProductTags']);
    Route::get('/tag/{tag_id}', [AdminController::class, 'showProductTag']);
    Route::post('/tag/store', [AdminController::class, 'storeProductTag']);
    Route::patch('/tag/{tag_id}/update', [AdminController::class, 'updateProductTag']);
    Route::delete('/tag/{tag_id}/delete', [AdminController::class, 'deleteProductTag']);
});

//* 商品
Route::prefix('v1/products')->group(function () {
    //? 所有商品
    Route::get('/', [ProductController::class, 'index']);
    //? 首頁的產品(包含Slider)
    Route::get('/indexPage', [ProductController::class, 'indexPageProducts']);
    //? 商品換頁 (Pagination)
    Route::get('/pagination/orderBy/{orderBy}/sortBy/{sortBy}', [ProductController::class, 'paginate']);
    //? 取得商品的標籤 
    Route::get('/tags/getTags', [ProductController::class, 'productTags']);
    //? 依標籤選擇商品
    Route::get('/tags/{id}', [ProductController::class, 'showByTag']);
    //? 單一商品 
    Route::get('/{id}', [ProductController::class, 'show']);
    //? 搜尋商品(含分頁) 
    Route::get('/search/{search}/pagination', [ProductController::class, 'searchWithPagination']);
    //? 搜尋字串自動補全 
    Route::get('/search/{search}/autocomplete', [ProductController::class, 'searchAutoComplete']);
});

//* 使用者
Route::prefix('v1/user')->group(function () {
    //? 註冊
    Route::post('/register', [UserController::class, 'register']);
    //? 登入
    Route::post('/login', [UserController::class, 'login']);
    //? 第三方登入
    Route::get('/socialiteLogin/{provider}/redirect', [UserController::class, 'socialiteRedirect']);
    Route::get('/socialiteLogin/{provider}/callback', [UserController::class, 'socialiteLogin']);
});

// ------------------------------以下操作必須包含 Token------------------------------ //
Route::prefix('v1/auth/user')->middleware('auth:sanctum')->group(function () {
    // ------------------------------使用者------------------------------ //
        //? 登出
    Route::get('/logout', [UserController::class, 'logout']);
        //? 取得登入的使用者
    Route::get('/getUser', [UserController::class, 'getCurrentUser']);
        //? 更新使用者的資料
    Route::patch('/updateProfile', [UserController::class, 'updateProfile']);
        //? 清空使用者的資料
    Route::patch('/clearProfile', [UserController::class, 'clearProfile']);
        //? 新增信用卡
    Route::post('/addCreditCard', [UserController::class, 'addCreditCard']);
        //? 刪除信用卡 
    Route::delete('/creditCards/{card_id}/delete', [UserController::class, 'deleteCreditCard']);
    // ------------------------------使用者------------------------------ //
    

    // ------------------------------購物車------------------------------ //
    //* 讀取
        //? 使用者的購物車
    Route::get('/cart', [CartController::class, 'show']);
    //* 新增
        //? 加入購物車
    Route::get('/cart/product/{product_id}/create', [CartController::class, 'create']);
        //? 加入購物車(包含數量、規格)
    Route::post('/cart/product/{product_id}/create', [CartController::class, 'create']);
    //* 修改
        //? 變更數量
    Route::post('/cart/product/{product_id}/update', [CartController::class, 'update']);
        //? 數量 + 1 
    Route::get('/cart/product/{product_id}/increaseByOne', [CartController::class, 'increaseByOne']);
        //? 數量 - 1 
    Route::get('/cart/product/{product_id}/decreaseByOne', [CartController::class, 'decreaseByOne']);
    //* 刪除
        //? 移除購物車內的商品(單項)
    Route::delete('/cart/product/{product_id}/delete', [CartController::class, 'destroy']);
        //? 清空購物車
    Route::delete('/cart/product/deleteAll', [CartController::class, 'destroyAll']);
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