<?php

namespace App\Http\Controllers;

//* 使用 Requests 來驗證
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest; 
use App\Http\Requests\LoginRequest;

//* Services 
use App\Services\UserService;

class UserController extends Controller
{
    //* 取得特定使用者
    public function getCurrentUser(UserService $service)
    {
        return $service->getLoggedInUser();
    }
    //* 建立使用者(註冊)
    public function register(RegisterRequest $request, UserService $service) {
        return $service->registerUser($request);
    }
    //* 登入使用者並授權 Token
    public function login(LoginRequest $request, UserService $service) {
        return $service->createBearerToken($request);
    }
    //* 登出使用者
    public function logout(Request $request, UserService $service) {
        return $service->deleteBearerToken($request);
    }
}
