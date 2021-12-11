<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Notification;
use App\Events\OrderCreatedEvent;
use App\Notifications\OrderCreated;

class OrderCreatedNotificationListener
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
    public function handle(OrderCreatedEvent $event)
    {
        //* 修正時間格式
        $created_at = now('Asia/Taipei')->format('Y-m-d H:i:s');
        //* 通知細節
        $details = [
            'title' => '商品訂購成功。',
            'avatar_url' => 'https://i.imgur.com/3JkI2Qo.png', // 圖片 URL
            'body' => "訂單編號 - " . $event->order->id,
            'created_at' => "訂購時間 - ". $created_at
        ];
        //* 發送通知 
        Notification::send($event->user, new OrderCreated($details));
    }
}
