<?php

namespace App\Http\Controllers;

use App\Models\User;

//* 使用第三方登入套件
use Laravel\Socialite\Facades\Socialite;

//* 使用 Requests 來驗證
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;


//* Services 
use App\Services\UserService;

//* 使用 exception 
use GuzzleHttp\Exception\ClientException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //* 取得特定使用者
    public function getCurrentUser(UserService $service)
    {
        return $service->getLoggedInUser();
    }
    //* 建立使用者(註冊)
    public function register(RegisterRequest $request, UserService $service)
    {
        return $service->registerUser($request);
    }
    //* 登入使用者並授權 Token
    public function login(LoginRequest $request, UserService $service)
    {
        //* 取得 email 相符的使用者
        $user = User::query()
            ->where('email', $request->email)
            ->first();
        //* 驗證使用者的 HASH 過後的密碼是否相符
        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['帳號或密碼錯誤'],
            ]);
        };

        return $service->createBearerToken($user);
    }

    public function socialiteRedirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function socialiteLogin(UserService $service, $provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided'], 422);
        }

        $user = User::where([
            'provider_id' => $socialiteUser->getId(),
            'provider' => $provider
        ])->first();

        if(!$user) {
            $user = User::create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
                'provider_id' => $socialiteUser->getId(),
                'provider' => $provider,
                'email_verified_at' => now()
            ]);
        }
        
        return $service->createBearerToken($user);
    }
    //* 登出使用者
    public function logout(Request $request, UserService $service)
    {
        return $service->deleteBearerToken($request);
    }

    public function updateProfile(UpdateProfileRequest $request, UserService $service)
    {
        return $service->updateUserProfile($request);
    }

    public function clearProfile(UserService $service)
    {
        return $service->clearUserProfile();
    }
}
