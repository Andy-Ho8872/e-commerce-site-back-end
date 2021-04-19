<?php

namespace App\Http\Controllers;

// Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;

// Requests
use App\Http\Requests\MakeOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $user_id, $carts, $user_orders;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // 取得已經登入的使用者
            $this->user_id = Auth::id();
            // 使用者的購物車
            $this->carts = Cart::where('user_id', $this->user_id);
            // 使用者的訂單
            $this->user_orders = Order::with(['items'])
                // 該筆訂單所購買的商品個數
                ->withCount('items')
                ->where('user_id', $this->user_id);

            return $next($request);
        });
    }

// 建立訂單
    public function createOrder(MakeOrderRequest $request)
    {
        // 確認購物車內是否有商品
        if ($this->carts->exists()) {
            // 建立訂單
            $order = Order::create([
                'user_id' => $this->user_id,
                'payment_id' => $request->payment_id,
                'address' => $request->address,
            ]);
            // 將資料寫入到 order_products 表格裡面做紀錄
            foreach ($this->carts->get() as $cart) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_quantity' => $cart->product_quantity
                ]);
            }
            // 回傳訊息
            $msg = "訂單建立成功";
            // 訂單建立後刪除購物車內的商品
            $this->carts->delete();
        } else {
            $msg = "購物車內沒有商品，訂單建立失敗。";
        }

        return response()->json(['msg' => $msg]);
    }

// 撈取訂單
    // 所有訂單
    public function getAllOrders()
    {
        $orders = $this->user_orders->get()
        ->append('sumSubtotal');

        // 付款與訂單欄位
        $payments = DB::table('payments')->get();
        $status = DB::table("status")->get();

        return response()->json(['orders' => $orders, 'payments' => $payments, 'status' => $status]);
    }
    // 單筆訂單
    public function getSingleOrder($order_id)
    {
        $order = $this->user_orders->findOrFail($order_id)
        ->append('sumSubtotal');

        return response()->json(['order' => $order]);
    }

// 刪除訂單
    public function deleteOrder($order_id)
    {
        $order = Order::where('user_id', $this->user_id)->where('id', $order_id);

        // 確認該筆訂單是否存在
        if ($order->exists()) {
            // 刪除該筆訂單
            $order->delete();
            // 提示訊息
            $msg = "您刪除了一筆訂單，訂單編號為${order_id}";
        } else {
            $msg = "該筆訂單不存在，操作失敗。";
        }

        return response()->json(['msg' => $msg]);
    }

// 前端表單資料
    public function getFormData()
    {
       $payments = DB::table('payments')->get();

       return response()->json(['payments' => $payments]);
    }
}
