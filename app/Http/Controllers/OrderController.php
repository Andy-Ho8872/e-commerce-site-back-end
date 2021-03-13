<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

// Models
use App\Models\Cart;
use App\Models\Order;

// Requests
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $user_id;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // 取得已經登入的使用者
            $this->user_id = Auth::user()->id;

            return $next($request);
        });
    }
    // 建立訂單
    public function createOrder(Request $request)
    {
        // 使用者購物車內的商品
        $carts = Cart::where('user_id', $this->user_id)->get();

        if ($carts) {
            foreach ($carts as $cart) {
                Order::create([
                    'user_id' => $this->user_id,
                    'payment_id' => $request->payment,
                    // 'status_id' => $request->status,  預設 1
                    'address' => $request->address,
                    // new
                    'product_id' => $cart->product_id,
                    'product_quantity' => $cart->product_quantity
                ]);
            }

            $msg = "訂單建立成功";

            // 訂單建立後刪除購物車內的商品
            Cart::where('user_id', $this->user_id)->delete();
        } else {
            $msg = "購物車內尚無商品，訂單建立失敗";
        }

        return response()->json(['msg' => $msg]);
    }
    // 撈取訂單
    public function getOrder()
    {
        $orders = Order::join('products', 'products.id', 'orders.product_id')
        ->select(
            'orders.id',
            'orders.user_id',
            'payment_id',
            'status_id',
            'address',
            'orders.product_id',
            'orders.product_quantity',
            'unit_price',
            'discount_rate',
            Order::raw('floor(unit_price * discount_rate) * product_quantity AS Total')
            // 總價...
        )
        ->where('orders.user_id', $this->user_id)
        ->get();

        return response()->json(['orders' => $orders]);

        // $orders = User::findOrFail($this->user_id)->orders;
        // return response()->json(['orders' => $orders]);
    }
    // 前端表單資料
    public function getFormData()
    {
    }
}
