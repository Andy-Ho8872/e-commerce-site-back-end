<?php

namespace App\Services;

//* Events
use App\Events\OrderCreatedEvent;

//* Facades
use Illuminate\Support\Facades\Auth;

//* Models
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderProduct;

//* Requests
use App\Http\Requests\OrderRequest;

class OrderService
{
    private $user_id, $carts, $user_orders;

    public function __construct()
    //* 常用變數
    {
        //* 取得已經登入的使用者
        $this->user_id = Auth::id();
        $this->user = User::find($this->user_id);
        //* 使用者的購物車
        $this->carts = Cart::where('user_id', $this->user_id);
        //* 使用者的訂單
        $this->user_orders = Order::query()
            ->with(['items'])
            ->withCount('items') //* 該筆訂單所購買的商品個數
            ->where('user_id', $this->user_id);
    }

    //* 建立訂單並發送通知
    public function createOrderAndSendNotification(OrderRequest $request)
    {
        //* 確認購物車內是否有商品
        if ($this->carts->exists()) {
            //? 建立訂單
            $order = Order::create([
                'user_id' => $this->user_id,
                'payment_id' => $request->payment_id,
                'address' => $request->address,
                'buyer_name' => $request->buyer_name,
                'buyer_phone' => $request->buyer_phone,
                'card_type' => $request->card_type,
                'card_number' => $request->card_number,
                'card_holder' => $request->card_holder,
                'card_expiration_date' => $request->card_expiration_month . '/' .$request->card_expiration_year,
                'card_CVV' => $request->card_CVV,
            ]);
            //? 將資料寫入到 order_product 表格裡面做紀錄
            $carts = $this->carts->get();
            $items = [];  //? 宣告為陣列
            foreach($carts as $cart) {
                $items[] = [
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_quantity' => $cart->product_quantity,
                    'variation_option_values' => json_encode($cart->variation_option_values), //* Array外圍必須加上json_encode()函式否則會跑出"array to string conversion"的錯誤
                ];
            }
            OrderProduct::insert($items);
            // 回傳訊息
            $msg = "訂單建立成功";
            //* 訂單建立後刪除購物車內的商品
            $this->carts->delete();   
            //* 推送通知
            $user = User::find($this->user_id);
            event(new OrderCreatedEvent($user, $order));
        } else {
            $msg = "購物車內沒有商品，訂單建立失敗。";
            return response()->json(['msg' => $msg], 400);
        }
        return response()->json(['msg' => $msg], 201);
    }

    //* 獲取使用者的訂單
    //? 所有的訂單
    public function getUserOrders()
    {
        $orders = $this->user_orders->get()
            ->append('sumSubtotal');

        return response()->json(['orders' => $orders]);
    } 

    //? 單筆訂單
    public function getUserSingleOrder($order_id)
    {
        $order = $this->user_orders->findOrFail($order_id)
            ->append('sumSubtotal');

        return response()->json(['order' => $order]);
    }

    public function deleteSingleOrder($order_id)
    {
        $order = Order::query()
        ->where('user_id', $this->user_id)
        ->where('id', $order_id);

        //* 確認該筆訂單是否存在
        if ($order->exists()) {
            //? 刪除該筆訂單
            $order->delete();
            // 提示訊息
            $msg = "您刪除了一筆訂單，訂單編號為${order_id}";
        } else {
            $msg = "該筆訂單不存在，操作失敗。";
            return response()->json(['msg' => $msg], 400);
        }

        return response()->json(['msg' => $msg], 201);
    }
}
