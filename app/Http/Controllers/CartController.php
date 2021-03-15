<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

// Models 
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;

// 驗證規則
use App\Http\Requests\CartRequest;

class CartController extends Controller
{
    // 常用變數
    private $user_id, $product_id, $itemInCart;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // 取得已經登入的使用者
            $this->user_id = Auth::id();
            // 欲加入的商品 id
            $this->product_id = $request->route('product_id');
            // 該使用者的購物車
            $this->itemInCart = Cart::where('user_id', $this->user_id)->where('product_id', $this->product_id);

            return $next($request);
        });
    }
// 查詢
    // 取得該使用者(id)的購物車 
    public function show()
    {
        // 該使用者的訂單
        $carts = Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->select(
                'carts.id', // 訂單 id
                'user_id',
                'product_id',
                'product_quantity', // 購買數量
                'title',
                'unit_price',
                'imgUrl',
                'discount_rate', // 折扣率
                // 總價 (取整數)
                Cart::raw('floor(unit_price * discount_rate) * product_quantity AS Total')
            )
            ->where('user_id', $this->user_id)->get();

        // $carts = User::with('carts')->findOrFail($this->user_id); // 測試用

        return response()->json(['carts' => $carts], 200);
    }
// 新增
    // 新增商品至購物車
    public function create(CartRequest $request)
    {
        // 該使用者的購物車
        $cart = $this->itemInCart;

        // 如果購物車內沒有該商品則寫入   
        if (!$cart->exists()) {
            // 有輸入商品數量
            if ($request->product_quantity) {
                Cart::create([
                    'user_id' => $this->user_id,
                    'product_id' => $this->product_id,
                    'product_quantity' => $request->product_quantity
                ]);
            }
            // 沒輸入商品數量
            else {
                Cart::create([
                    'user_id' => $this->user_id,
                    'product_id' => $this->product_id,
                    'product_quantity' => 1 // 預設 1 個
                ]);
            }
            // 回傳訊息
            $msg = "您新增了商品至購物車";
        }
        // 如果購物車內已經存在該商品
        else {
            // 有輸入商品數量
            if ($request->product_quantity) {
                $cart->increment('product_quantity', $request->product_quantity);
                $msg = "新增的商品已重複，數量增加";
            }
            // 沒輸入商品數量
            else {
                $cart->increment('product_quantity', 1); // 重複點擊(未指定數量) 則增加 1
                $msg = "商品數量 + 1 ";
            }
        }

        $item = Product::select('id', 'title', 'unit_price')->findOrFail($this->product_id);

        return response()->json(['item' => $item, 'msg' => $msg, 'user_id' => $this->user_id], 201);
    }
// 修改
    // 修改購物車內商品數量
    public function update(CartRequest $request)
    {
        $this->itemInCart->update([
            'product_quantity' => $request->product_quantity
        ]);

        // 回傳訊息
        $msg = "您更改了商品數量，請查看";

        return response()->json(['msg' => $msg], 201);
    }
    // 數量 + 1
    public function increseByOne()
    {
        $this->itemInCart->increment('product_quantity', 1);

        // 回傳訊息
        $msg = "您更改了商品數量 + 1，請查看";

        return response()->json(['msg' => $msg], 201);
    }
    //  數量 - 1
    public function decreseByOne()
    {
        $cart = $this->itemInCart->first(); // 要使用 first() 讀取到該物件的 property

        // 數量至少要大於 1 才能減少
        if ($cart->product_quantity > 1) {
            $cart->decrement('product_quantity', 1);
            $msg = "您更改了商品數量 - 1，請查看";
        } 
        else {
            $msg = "商品數量並未更動";
        }

        return response()->json(['cart' => $cart, 'msg' => $msg], 201);
    }
// 刪除
    // 移除購物車內的商品 (單筆)
    public function destroy()
    {
        $this->itemInCart->delete();

        // 回傳訊息
        $msg = "您移除了一項商品，請查看";

        return response()->json(['msg' => $msg], 201);
    }
// 清空購物車
    public function destroyAll()
    {
        Cart::where('user_id', $this->user_id)->delete();

        // 回傳訊息
        $msg = "已經清空您的購物車";

        return response()->json(['msg' => $msg], 201);
    }
}
