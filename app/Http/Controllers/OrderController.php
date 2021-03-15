<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

// Models
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderProduct;

// Requests
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $user_id, $carts;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // 取得已經登入的使用者
            $this->user_id = Auth::id();
            // 使用者的購物車
            $this->carts = Cart::where('user_id', $this->user_id);

            return $next($request);
        });
    }
    // 建立訂單
    public function createOrder(Request $request)
    {
        // 確認購物車內是否有商品
        if ($this->carts->exists()) {
            // 建立訂單
            $order = Order::create([
                'user_id' => $this->user_id,
                'payment_id' => $request->payment,
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
    public function getOrder()
    {
        // $orders = Order::join('products', 'products.id', 'orders.product_id')
        // ->select(
        //     'orders.id',
        //     'orders.user_id',
        //     'payment_id',
        //     'status_id',
        //     'address',
        //     'orders.product_id',
        //     'orders.product_quantity',
        //     'unit_price',
        //     'discount_rate',
        //     Order::raw('floor(unit_price * discount_rate) * product_quantity AS Total')
        //     // 總價...
        // )
        // ->where('orders.user_id', $this->user_id)
        // ->get();

        // return response()->json(['orders' => $orders]);

        
        // $orders = User::findOrFail($this->user_id)->orders;

        $orders = Order::with(['items'])->where('user_id', $this->user_id)->get();

        return response()->json(['orders' => $orders]);
    }
    // 前端表單資料
    public function getFormData()
    {
    }
}
