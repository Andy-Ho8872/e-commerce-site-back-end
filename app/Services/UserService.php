<?php

namespace App\Services;

use App\Events\UserRegisteredEvent;

//* Models
use App\Models\User;
use App\Models\CreditCard;

//* Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; //? 密碼加密功能

//* Requests
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

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
            ->with(['orders', 'credit_cards'])
            ->findOrFail($this->user_id);

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
        //* 推送通知 
        event(new UserRegisteredEvent($user));
        // 顯示訊息
        $msg = "使用者 $request->email 註冊成功";

        return response()->json(['user' => $user, 'message' => $msg], 201);
    }

    public function createBearerToken($user)
    {
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

    public function storeCreditCard(Request $request) 
    {   
        CreditCard::create([
            'user_id' => $this->user_id,
            'card_type' => $request->type,
            'card_number' => $request->number,
            'card_holder' => $request->holder_name,
            'card_expiration_date' => $request->expiration_month, //* ex: 04/28
            'card_CVV' => $request->cvv,
        ]);

        $msg = "信用卡新增成功";

        return response()->json(["msg" => $msg], 201);
    }
}
