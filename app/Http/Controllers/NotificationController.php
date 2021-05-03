<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

//* Models
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $user_id, $user;

    public function __construct()
    //* 常用變數
    {
        $this->middleware(function ($request, $next) {
            //* 取得已經登入的使用者
            $this->user_id = Auth::id();
            $this->user = User::find($this->user_id);

            return $next($request);
        });
    }

    //* 獲取通知
    public function getHasReadNotification()
    {
        $notifications = $this->user->notifications;

        return response()->json(['notifications' => $notifications]);
    }
    public function getUnReadNotification()
    {
        $unReadNotifications = $this->user->unreadNotifications;

        return response()->json(['unReadNotifications' => $unReadNotifications]);
    }

    //* 標示為已讀 
        //* 單筆通知 
    public function markNotification(Request $request)
    {
        $this->user->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })->markAsRead();

        $msg = "已經將該通知標示為已讀";

        return response()->json(['msg' => $msg], 200);
    }
        //* 所有通知
    public function markAllNotification()
    {
        $this->user->unreadNotifications->markAsRead();
        $msg = "已經將所有通知標示為已讀";

        return response()->json(['msg' => $msg], 200);
    }

    //* 刪除所有通知
    public function deleteAllNotification()
    {
        $this->user->notifications()->delete();
        $msg = "已經清除所有通知";

        return response()->json(['msg' => $msg], 201);
    }
}
