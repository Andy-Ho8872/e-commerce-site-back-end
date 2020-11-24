<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
//use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\UserController;



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

// 包含 Controller 預設的所有 methods  ex: index, store, show, destroy...etc 
Route::resource('products', ProductController::class)->middleware('auth:sanctum');

// 127.0.0.1:8000/api/auth/user
// 註冊使用者
Route::post('/auth/register', [UserController::class, 'register'])->name('user.register');
// 登入使用者
Route::post('/auth/login', [UserController::class, 'login'])->name('user.login');
// 登出使用者
Route::post('/auth/logout', [UserController::class, 'logout'])->middleware('auth:sanctum'); 
// 取得所有使用者
Route::get('/auth/user', [UserController::class, 'index'])->middleware('auth:sanctum');












// Create a product (POST) /api/products


// Get single products (GET) /api/products/{id}


// Update single product (PUT/PATCH) /api/products/{id}


// Delete product (DELETE) /api/products/{id}
