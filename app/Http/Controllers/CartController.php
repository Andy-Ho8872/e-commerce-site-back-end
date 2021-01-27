<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

// Models 
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
// 驗證規則
use App\Http\Requests\CartRequest;


class CartController extends Controller
{
// 查詢
    // 取得該使用者(id)的購物車 
    public function show() 
    {
        // 使用者必須先登入
        $user_id = Auth::user()->id;

        // 該使用者的訂單
        $orders = Order::join('products', 'orders.product_id', '=', 'products.id')
        ->select(
            'orders.id', // 訂單 id
            'user_id',
            'product_id', 
            'product_quantity', // 購買數量
            'title', 
            'unit_price', 
            'imgUrl', 
            'discount_rate', // 折扣率
            // 總價 (取整數)
            Order::raw('floor(unit_price * product_quantity * discount_rate) AS Total')   
        )
        ->where('user_id', $user_id)->get();

        return response()->json(['orders' => $orders, 'user_id' => $user_id], 200);
    }

// 新增
    // 新增商品至購物車
    public function create(CartRequest $request, $product_id) 
    {
        // 找到特定使用者
        $user_id = Auth::user()->id;

        // 特定產品
        $product = Product::findOrFail($product_id);

        // 使用者想加入的商品
        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        // 如果購物車內沒有該商品則寫入   
        if(! $order->exists()) {
            // 有輸入商品數量
            if ($request->product_quantity) { 
                $order->create([ // $order = new Order
                    'user_id' => $user_id,
                    'product_id' => $product->id,
                    'product_quantity' => $request->product_quantity
                ]);
            }
            // 沒輸入商品數量
            else {
                $order->create([
                    'user_id' => $user_id,
                    'product_id' => $product->id,
                    'product_quantity' => 1 // 預設 1 個
                ]);
            }
            // 回傳訊息
            $msg = "您新增了商品至購物車";
        } 
        // 如果購物車內已經存在該商品
        else {
            // 有輸入商品數量
            if($request->product_quantity) {
                $order->increment('product_quantity', $request->product_quantity);
                $msg = "新增的商品已重複，數量增加";
            }
            // 沒輸入商品數量
            else {
                $order->increment('product_quantity', 1); // 重複點擊(未指定數量) 則增加 1
                $msg = "商品數量 + 1 ";
            }
        }
        return response()->json(['order' => $order, 'msg' => $msg, 'user' => $user_id], 201);
    }

// 修改
    // 修改購物車內商品數量
    public function update(CartRequest $request, $product_id)
    {
        $user_id = Auth::user()->id;

        // 選擇 相同使用者、相同商品的資料
        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        // 變更購買數量
        $order->update([
            'product_quantity' => $request->product_quantity
        ]);
        
        // 回傳訊息
        $msg = "您更改了商品數量，請查看";

        return response()->json(['order' => $order , 'msg' => $msg], 201);
    }

    // 數量 + 1
    public function increseByOne($product_id)
    {
        $user_id = Auth::user()->id;

        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        $order->increment('product_quantity', 1); 

        // 回傳訊息
        $msg = "您更改了商品數量 + 1，請查看";

        $order = User::find($user_id)->order;

        return response()->json(['order' => $order, 'msg' => $msg], 201);
    }

    //  數量 - 1
    public function decreseByOne($product_id)
    {
        $user_id = Auth::user()->id;

        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        $order->decrement('product_quantity', 1);

        // 回傳訊息
        $msg = "您更改了商品數量 - 1，請查看";

        $order = User::find($user_id)->order;

        return response()->json(['order' => $order, 'msg' => $msg], 201);
    }


// 刪除
    // 移除購物車內的商品 (單筆)
    public function destroy($product_id)
    {
        $user_id = Auth::user()->id;

        $order = Order::where('user_id', $user_id)
        ->where('product_id', $product_id);

        // 清除單項商品
        $order->delete();

        // 回傳訊息
        $msg = "您移除了一項商品，請查看";

        return response()->json(['order' => $order, 'msg' => $msg], 201);
    }

    // 清空購物車
    public function destroyAll()
    {
        $user_id = Auth::user()->id;

        Order::where('user_id', $user_id)->delete();

        // 回傳訊息
        $msg = "已經清空您的購物車";

        return response()->json(['msg' => $msg], 201);
    }
}
