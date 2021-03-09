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
        $cart = Cart::where('user_id', $this->user_id);

        $order = Order::create([
            'user_id' => $this->user_id,
            'payment_id' => $request->payment,
            // 'status_id' => $request->status,  預設 1
            'delivery_id' => $request->delivery,
            'address' => $request->address,
            // new
            'product_id' => $cart->product_id,
            'product_quantity' => $cart->product_quantity
        ]);

        $msg = "訂單建立成功";

        return response()->json(['order' => $order, 'msg' => $msg]);
    }
    // 撈取訂單
    public function getOrder() 
    {
        // $orders = Order::join('carts', 'orders.user_id', 'carts.user_id')
        // ->join('products', 'products.id', 'carts.product_id')
        // ->select(
        //     'orders.id',
        //     'orders.user_id',
        //     'payment_id',
        //     'status_id',
        //     'delivery_id',
        //     'address',
        //     'product_id',
        //     'product_quantity',
        //     'unit_price',
        //     'discount_rate',
        //     Order::raw('floor(unit_price * discount_rate) * product_quantity AS Total')
        //     // 總價...
        // )
        // ->where('orders.user_id', $this->user_id)
        // ->get();

        // return response()->json(['orders' => $orders]);
    }
    // 前端表單資料
    public function getFormData() 
    {

    }
}
