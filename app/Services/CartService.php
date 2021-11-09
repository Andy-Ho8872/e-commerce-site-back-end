<?php

namespace App\Services;

// Facades
use Illuminate\Support\Facades\Auth;

// Requests
use App\Http\Requests\CartRequest;

// Models
use App\Models\Cart;
use App\Models\Product;

class CartService
{
    //* 常用變數
    private $user_id, $product_id, $itemInCart, $itemInCartExists;

    public function __construct(CartRequest $request)
    {
        //* 取得已經登入的使用者
        $this->user_id = Auth::id();
        //* 欲加入的商品 id
        $this->product_id = $request->route('product_id');
        //* 該使用者的購物車商品
        $this->itemInCart = Cart::query()
            ->where('user_id', $this->user_id)
            ->where('product_id', $this->product_id);
        //* 商品是否存在於購物車
        $this->itemInCartExists = $this->itemInCart->exists();
    }

    public function showUserCart()
    {
        // $carts = Cart::query()
        //     ->join('products', 'carts.product_id', 'products.id')
        //     ->select([
        //         'carts.id', //? 訂單 id
        //         'user_id',
        //         'product_id',
        //         'product_quantity', //? 購買數量
        //         'title',
        //         'unit_price',
        //         'imgUrl',
        //         'discount_rate', //? 折扣率
        //         //? 總價 (取整數)
        //         Cart::raw('floor(unit_price * discount_rate) * product_quantity AS total')
        //     ])
        //     ->where('user_id', $this->user_id)
        //     ->get();

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
            ->get()
            ->groupBy('variation_option_values'); // 若 variation 或 option 的值有相異則為不同組

        return response()->json(['carts' => $carts], 200);
    }

    public function addItemToCart(CartRequest $request)
    {
        $product_exists = Product::where('id', $this->product_id)->exists();
        //? 輸入的商品數量 
        $product_quantity = $request->input('product_quantity', 1);
        //? 該使用者的購物車
        $cart = $this->itemInCart;

        //* 判定該商品是否存在 
        if (!$product_exists) {
            $msg = "該商品不存在，此次操作無效";
            return response()->json(["msg" => $msg], 404);
        }

        //* 如果購物車內沒有該商品則寫入   
        if (!$cart->exists()) {
            Cart::create([
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'product_quantity' => $product_quantity //* 未輸入數量的話 預設值 1
            ]);
            $msg = "您新增了商品至購物車，商品編號為 $this->product_id";
        } else {
            //* 如果購物車內已經存在該商品
            $cart->increment('product_quantity', $product_quantity); //* 未輸入數量的話 預設值 1
            $msg = "新增的商品已重複，該商品數量增加，商品編號為 $this->product_id";
        }

        return response()->json(['msg' => $msg], 201);
    }

    public function updateQuantityByInput(CartRequest $request)
    {
        if (!$this->itemInCartExists) {
            $msg = "該商品不存在，操作無效";
            return response()->json(['msg' => $msg], 404);
        } 

        if (!$request->has('product_quantity')) {
            $msg = "您未輸入數量，請輸入數量更改";
            return response()->json(['msg' => $msg], 400);
        }

        $this->itemInCart->update([
            'product_quantity' => $request->input('product_quantity')
        ]);
        // 回傳訊息
        $msg = "您更改了購物車中的商品數量，請查看";
        return response()->json(['msg' => $msg], 201);
    }
    public function increaseQuantityByOne()
    {
        if (!$this->itemInCartExists) {
            $msg = "該商品不存在，操作無效";
            return response()->json(['msg' => $msg], 404);
        } 

        $this->itemInCart->increment('product_quantity', 1);
        // 回傳訊息
        $msg = "您增加了購物車中的商品數量，請查看";
        return response()->json(['msg' => $msg], 201);
    }

    public function decreaseQuantityByOne()
    {
        $cart = $this->itemInCart->first(); //* 要使用 first() 讀取到該物件的 property

        if (!$this->itemInCartExists) {
            $msg = "該商品不存在，操作無效";
            return response()->json(['msg' => $msg], 404);
        } 

        //* 數量至少要大於 1 才能減少
        if ($cart->product_quantity > 1) {
            $cart->decrement('product_quantity', 1);
            $msg = "您減少了購物車中的商品數量，請查看";
        } else {
            $msg = "商品數量最少為 1，此次更動無效。";
        }

        return response()->json(['msg' => $msg], 201);
    }

    public function deleteItemFromCart()
    {
        if (!$this->itemInCartExists) {
            $msg = "該商品不存在，操作無效";
            return response()->json(['msg' => $msg], 404);
        } 

        $this->itemInCart->delete();
        // 回傳訊息
        $msg = "您移除了購物車中的一項商品，請查看";
        return response()->json(['msg' => $msg], 201);
    }
    public function deleteAllItemsFromCart()
    {   
        $exist = Cart::where('user_id', $this->user_id)->exists();
        if(!$exist) {
            $msg = "購物車中沒有任何商品，此次操作無效";
            return response()->json(['msg' => $msg], 404);
        }

        Cart::where('user_id', $this->user_id)->delete();
        // 回傳訊息
        $msg = "已經清空您的購物車";
        return response()->json(['msg' => $msg], 201);
    }
}
