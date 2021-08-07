<?php

namespace App\Services;
//* Models
use App\Models\User;

//* Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; //? 密碼加密功能

//* Requests
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
//* Error Exception
use Illuminate\Validation\ValidationException; //? 顯示錯誤訊息

class UserService
{
    public function getLoggedInUser()
    {
        $user_id = Auth::id();

        $user = User::query()
            ->with('orders')
            ->findOrFail($user_id);

        // 回傳資料庫中的使用者資訊
        return response()->json(['user' => $user], 200);
    }

    public function registerUser(RegisterRequest $request)
    {
        //* 接收表單的資料，驗證都通過後儲存到資料庫中
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        // 顯示訊息
        $msg = "使用者 $request->email 註冊成功";
        return response()->json(['user' => $user, 'message' => $msg], 201);
    }

    public function createBearerToken(LoginRequest $request)
    {
        //* 取得 email 相符的使用者
        $user = User::query()
            ->where('email', $request->email)
            ->first();
        //* 驗證使用者的 HASH 過後的密碼是否相符
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['帳號或密碼錯誤'],
            ]);
        };
        //* 若驗證通過，則建立 Bearer Token 給該使用者。 ex: Bearer *$&*(#%^&*$)65498746134aaaa
        $token = $user->createToken('user-token')->plainTextToken;
        //* 回傳 Token 與 user 的詳細資訊
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function deleteBearerToken(Request $request)
    {
        //* 撤銷該使用者 Token
        $request->user()->tokens()->delete();
        // 回傳結果
        return response()->json('您已登出', 201);
    }
}