<?php

namespace App\Services;

use Carbon\Carbon; //? 處理時間的函式庫

//* Models
use App\Models\User;

//* Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; //? 密碼加密功能
use Illuminate\Support\Facades\Notification; //? 推送通知功能

//* Requests
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

//* Error Exception
use Illuminate\Validation\ValidationException; //? 顯示錯誤訊息

//* Notification
use App\Notifications\UserRegistered;

class UserService
{
    private $user_id;

    public function __construct()
    {
        $this->user_id = Auth::id();
    }

    public function getLoggedInUser()
    {
        $user = User::query()
            ->with('orders')
            ->findOrFail($this->user_id);

        // 回傳資料庫中的使用者資訊
        return response()->json(['user' => $user], 200);
    }
    public function notifyWhenUserRegistered($user)
    {
        $user_id = User::find($user->id);
        //* 修正時間格式
        $created_at = Carbon::now('Asia/Taipei')->format('Y-m-d H:i:s');
        //* 通知細節
        $details = [
            'title' => '歡迎您的加入。',
            'avatar_url' => 'https://i.imgur.com/DDHgSks.png', // 圖片 URL
            'body' => "親愛的 $user->email 我們非常高興你能使用我們的服務。",
            'created_at' => "加入時間 - $created_at"
        ];
        //* 發送通知 
        Notification::send($user_id, new UserRegistered($details));
    }
    public function registerUser(RegisterRequest $request)
    {
        //* 接收表單的資料，驗證都通過後儲存到資料庫中
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        //* 推送通知 
        $this->notifyWhenUserRegistered($user);
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

    public function updateUserProfile(UpdateProfileRequest $request) 
    {
        $user = User::where('id', $this->user_id);
        $user->update([
            'name' => $request->user_name,
            'phone' => $request->user_phone,
            'address' => $request->user_address,
        ]);

        return response()->json(['message' => "個人資料更改完成"], 201);
    }

    public function clearUserProfile() 
    {
        $user = User::where('id', $this->user_id);
        $user->update([
            'name' => null,
            'phone' => null,
            'address' => null,
        ]);

        return response()->json(['message' => "個人資料已經清空"], 201);
    }
}
