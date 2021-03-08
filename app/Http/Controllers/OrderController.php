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

    public function createOrder(Request $request)
    {
        $order = Order::create([
            'user_id' => $this->user_id,
            'payment_id' => $request->payment,
            // 'status_id' => $request->status,  預設 1
            'delivery_id' => $request->delivery,
            'address' => $request->address
        ]);

        $msg = "訂單建立成功";

        return response()->json(['order' => $order, 'msg' => $msg]);
    }

    public function getOrder() 
    {
        $orders = Order::join('carts', 'orders.user_id', 'carts.user_id')
        ->select(
            'orders.id',
            'orders.user_id',
            'payment_id',
            'status_id',
            'delivery_id',
            'address',
            'carts.prodcut_id',
            'carts.product_quantity',
            // 總價...
        )->where('user_id', $this->user_id);

        return response()->json(['orders' => $orders]);
    }
}
