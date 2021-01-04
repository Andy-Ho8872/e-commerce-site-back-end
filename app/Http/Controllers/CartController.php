<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models 
use App\Models\User;
use App\Models\Product;
use App\Models\Order;


class CartController extends Controller
{
    // 取得該使用者(id)的購物車 也許可以使用 group by
    public function show($user_id) 
    {
        $orders = Order::join('products', 'orders.product_id', '=', 'products.id')
        ->select('orders.id', // 訂單 id
            'user_id',
            'product_id', 
            'product_quantity', 
            'title', 
            'unit_price', 
            'imgUrl', 
            'discount_rate',
            // 總價 (取整數)
            Order::raw('floor(unit_price * product_quantity * discount_rate) AS Total')   
        )
        ->where('user_id', $user_id) // 該使用者 id
        ->get();

        // $orders = User::find($user_id)->orders;

        return response()->json(['orders' => $orders]);
    }


    // 新增商品至購物車
    public function store(Request $request, $user_id, $product_id) 
    {
        $order = new Order();

        // 找到特定使用者
        $user = User::findOrFail($user_id);

        // 特定產品
        $product = Product::findOrFail($product_id);

        // 購買者 id
        $order->user_id = $user->id;

        // 產品 id
        $order->product_id = $product->id;

        // 購買數量
        $order->product_quantity = 1;

        // 確認使用者購買的物品是否和上一單一樣 ?
        

        // 儲存至資料庫
        $order->save();

        // 回傳訊息
        $msg = "您新增了商品至購物車";

        return response()->json(['order' => $order, 'msg' => $msg], 201);
    }


    // 更新購物車的內容 (僅限相同使用者的相同商品)
    public function update(Request $request, $user_id, $product_id)
    {
        // 選擇 相同使用者、相同商品的資訊
        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        // 變更購買數量
        $order->update([
            'product_quantity' => $request->product_quantity
        ]);
        
        // 回傳訊息
        $msg = "您更改了商品數量，請查看";

        // 更新後的內容
        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id)
        ->get();
        
        return response()->json(['order' => $order , 'msg' => $msg], 201);
    }

    public function increseByOne(Request $request, $user_id, $product_id)
    {
        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        // 測試
        // $increment = 12; 

        $order->update([
            'product_quantity' => $request->product_quantity + 1
        ]);

        // 回傳訊息
        $msg = "您更改了商品數量，請查看";

        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id)
        ->get();

        return response()->json(['order' => $order, 'msg' => $msg], 201);
    }

    public function decreseByOne(Request $request, $user_id, $product_id)
    {
        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        $order->update([
            'product_quantity' => $request->product_quantity - 1
        ]);

        // 回傳訊息
        $msg = "您更改了商品數量，請查看";

        return response()->json(['order' => $order, 'msg' => $msg], 201);
    }


    // 移除購物車的內容(單項)
    public function destroy($user_id, $product_id)
    {
        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        // 測試
        // $order = Order::find($id);

        // 清除單項商品
        $order->delete();

        // 回傳訊息
        $msg = "您移除了一項商品，請查看";

        return response()->json(['msg' => $msg], 201);
    }

    // 清空購物車
    public function destroyAll($user_id)
    {
        Order::where('user_id', $user_id)->delete();

        // 回傳訊息
        $msg = "已經清空您的購物車";

        return response()->json(['msg' => $msg]);
    }
}
