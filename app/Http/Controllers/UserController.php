<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // 密碼加密功能
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // 顯示錯誤訊息

// 使用 Model
use App\Models\User; 

// 使用 Request 來驗證
use App\Http\Requests\RegisterRequest; 
use App\Http\Requests\LoginRequest;

class UserController extends Controller
{
   // 列舉出所有使用者
    public function index()
    {
        // 列出所有使用者
        $users = User::all(); 

        // 回傳資料庫中的所有使用者資訊
        return $users;
    }
    
    // 取得特定使用者
    public function getCurrentUser()
    {
        $user_id = Auth::id();

        $user = User::with('orders')->findOrFail($user_id); 

        // 回傳資料庫中的使用者資訊
        return response()->json(['user' => $user], 200);
    }


    // 建立使用者(註冊)
    public function register(RegisterRequest $request) {
        
        // 接收表單的資料，驗證都通過後儲存到資料庫中
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        
        // 顯示訊息
        $msg = "使用者 $request->email 註冊成功";
        
        // 回傳詳細資訊
        return response()->json(['user' => $user, 'message' => $msg], 201); 
    }

    // 登入使用者並授權 Token
    public function login(LoginRequest $request) {
        
        // 取得 email 相符的使用者 (第一筆)
        $user = User::where('email', $request->email)->first(); 

        // 驗證使用者的 HASH 過後的密碼是否相符
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['帳號或密碼錯誤'],
            ]);
        };
        
        // 若驗證通過 則建立 Token 給該使用者，且 Token 前面必須要加上 Bearer    ex: Bearer *$&*(#%^&*$)65498746134aaaa
        $token = $user->createToken('user-token')->plainTextToken;

        // 回傳 Token 與 user 的詳細資訊
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    // 登出使用者
    public function logout(Request $request) {
        
        // 撤銷該使用者 Token
        $request->user()->tokens()->delete();
    
        // 回傳結果
        return response()->json('您已登出', 201);
    }
}
