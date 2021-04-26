<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

//* Models 
use App\Models\Product;
use App\Models\Cart;


//* 驗證規則
use App\Http\Requests\CartRequest;

class CartController extends Controller
{
    //* 常用變數
    private $user_id, $product_id, $itemInCart;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //* 取得已經登入的使用者
            $this->user_id = Auth::id();
            //* 欲加入的商品 id
            $this->product_id = $request->route('product_id');
            //* 該使用者的購物車商品
            $this->itemInCart = Cart::query()
                ->where('user_id', $this->user_id)
                ->where('product_id', $this->product_id);

            return $next($request);
        });
    }
//* 查詢
    //* 取得該使用者(id)的購物車 
    public function show()
    {
        $carts = Cart::query()
            ->join('products', 'carts.product_id', 'products.id')
            ->select([
                'carts.id', //? 訂單 id
                'user_id',
                'product_id',
                'product_quantity', //? 購買數量
                'title',
                'unit_price',
                'imgUrl',
                'discount_rate', //? 折扣率
                //? 總價 (取整數)
                Cart::raw('floor(unit_price * discount_rate) * product_quantity AS total')
            ])
            ->where('user_id', $this->user_id)
            ->get();

        // $carts = User::with(['carts'])->findOrFail($this->user_id); //todo 測試用

        return response()->json(['carts' => $carts], 200);
    }
//* 新增
    //? 新增商品至購物車
    public function create(CartRequest $request)
    {
        //? 該使用者的購物車
        $cart = $this->itemInCart;

        //* 如果購物車內沒有該商品則寫入   
        if (!$cart->exists()) {
            Cart::create([
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'product_quantity' => $request->input('product_quantity', 1) //* 未輸入數量的話 預設值 1
            ]);
            // 回傳訊息
            $msg = "您新增了商品至購物車";
        }
        //* 如果購物車內已經存在該商品
        else {
            $cart->increment('product_quantity', $request->input('product_quantity', 1)); //* 未輸入數量的話 預設值 1
            $msg = "新增的商品已重複，數量增加";
        }

        $item = Product::select('id', 'title', 'unit_price')->findOrFail($this->product_id);

        return response()->json(['item' => $item, 'msg' => $msg, 'user_id' => $this->user_id], 201);
    }
//* 修改
    //? 修改購物車內商品數量
    public function update(CartRequest $request)
    {
        if ($request->has('product_quantity')) {
            $this->itemInCart->update([
                'product_quantity' => $request->input('product_quantity')
            ]);
            // 回傳訊息
            $msg = "您更改了商品數量，請查看";
        } else {
            $msg = "您未輸入數量，請輸入數量更改";
            return response()->json(['msg' => $msg], 400);
        }

        return response()->json(['msg' => $msg], 201);
    }
    //? 數量 + 1
    public function increseByOne()
    {
        $this->itemInCart->increment('product_quantity', 1);

        // 回傳訊息
        $msg = "您更改了商品數量 + 1，請查看";

        return response()->json(['msg' => $msg], 201);
    }
    //? 數量 - 1
    public function decreseByOne()
    {
        $cart = $this->itemInCart->first(); //* 要使用 first() 讀取到該物件的 property

        //* 數量至少要大於 1 才能減少
        if ($cart->product_quantity > 1) {
            $cart->decrement('product_quantity', 1);
            $msg = "您更改了商品數量 - 1，請查看";
        } else {
            $msg = "商品數量並未更動";
        }

        return response()->json(['cart' => $cart, 'msg' => $msg], 201);
    }
//* 刪除
    //? 移除購物車內的商品 (單筆)
    public function destroy()
    {
        $this->itemInCart->delete();

        // 回傳訊息
        $msg = "您移除了一項商品，請查看";

        return response()->json(['msg' => $msg], 201);
    }
    //? 清空購物車
    public function destroyAll()
    {
        Cart::where('user_id', $this->user_id)->delete();

        // 回傳訊息
        $msg = "已經清空您的購物車";

        return response()->json(['msg' => $msg], 201);
    }
}
