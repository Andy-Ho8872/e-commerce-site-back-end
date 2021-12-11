<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Events\UserRegisteredEvent;
use App\Notifications\UserRegistered;

class UserRegisteredNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegisteredEvent $event)
    {
        //* 修正時間格式
        $created_at = now('Asia/Taipei')->format('Y-m-d H:i:s');
        //* 通知細節
        $details = [
            'title' => '歡迎您的加入。',
            'avatar_url' => 'https://i.imgur.com/DDHgSks.png', // 圖片 URL
            'body' => "親愛的 " . $event->user->email . " 我們非常高興你能使用我們的服務。",
            'created_at' => "加入時間 - " . $created_at
        ];
        //* 發送通知 
        Notification::send($event->user, new UserRegistered($details));
    }
}
