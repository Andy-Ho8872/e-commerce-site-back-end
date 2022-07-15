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
        //* 購物車 id 
        $this->cart_id = $request->route('cart_id');
        //* 該使用者的購物車
        $this->itemInCart = Cart::query()
            ->where('user_id', $this->user_id)
            ->where('id', $this->cart_id);
        //* 購物車內是否有商品
        $this->itemInCartExists = $this->itemInCart->exists();
    }

    public function showUserCart()
    {
        //* 當前版本 */ 
        $carts = Cart::query()
            ->join('products', 'carts.product_id', 'products.id')
            ->select([
                'carts.id', //? 訂單 id
                'user_id',
                'product_id',
                'stock_quantity', //? 商品庫存數量
                'product_quantity', //? 購買數量
                'title',
                'variation_option_values',
                'unit_price',
                'imgUrl',
                'discount_rate', //? 折扣率
                //? 總價 (取整數)
                Cart::raw('floor(unit_price * discount_rate) * product_quantity AS total')
            ])
            ->where('user_id', $this->user_id)
            ->get();

        return response()->json(['carts' => $carts], 200);
    }

    public function addItemToCart(CartRequest $request)
    {
        //? 輸入的商品數量 
        $product_quantity = $request->input('product_quantity', 1);
        //? 規格選項
        $variation_option_values = $request->input('variation_option_values', []);
        //* 判定購物車內是否有相同的商品 
        $user_cart_items = Cart::query()
            ->where('user_id', $this->user_id)
            ->where('product_id', $this->product_id);
        //* 判定該商品是否存在 
        if (!Product::where('id', $this->product_id)->exists()) {
            $type = "error";
            $msg = "該商品不存在，此次操作無效";
            return response()->json(["msg" => $msg, "type" => $type], 404);
        }
        //* 購買數量限制 
        if ($product_quantity > Product::where('id', $this->product_id)->first()->stock_quantity) {
            $type = "warning";
            $msg = "已經超出最大購買數量，請於購物車內確認";
            return response()->json(["msg" => $msg, "type" => $type], 400);
        }
        $has_difference = true;
        if (count($user_cart_items->get()) > 0) {
            //* 比對當前所選規格是否已經和資料庫內的重複
            foreach ($user_cart_items->get() as $item) {
                $diff = array_diff($variation_option_values, $item->variation_option_values);
                //* 資料表中有差異才新增
                if (count($diff) == 0) {
                    $has_difference = false;
                }
            }
        }
        //* 有選擇規格且並無重複
        if (count($variation_option_values) > 0 && $has_difference) {
            Cart::create([
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'product_quantity' => $product_quantity,
                'variation_option_values' => $variation_option_values
            ]);
            $type = "success";
            $msg = "成功新增至購物車";
            return response()->json(['msg' => $msg, 'type' => $type], 201);
        }
        //* 若並無規格可選且購物車內並無商品
        if (count($variation_option_values) === 0 && !$user_cart_items->exists()) {
            Cart::create([
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'product_quantity' => $product_quantity,
                'variation_option_values' => $variation_option_values
            ]);
            $type = "success";
            $msg = "成功新增至購物車";
            return response()->json(['msg' => $msg, 'type' => $type], 201);
        } else {
            //* 如果購物車內已經存在該商品
            $type = "warning";
            $msg = "新增的商品已重複，請於購物車內查看";
            return response()->json(['msg' => $msg, 'type' => $type], 200);
        }
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

        $this->itemInCart->update(['product_quantity' => $request->input('product_quantity')]);
        // 回傳訊息
        $msg = "您更改了購物車中的商品數量，請查看";
        $type = "warning";
        return response()->json(['msg' => $msg, 'type' => $type], 201);
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
        $type = "warning";
        return response()->json(['msg' => $msg, 'type' => $type], 201);
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
            $type = "warning";
        } else {
            $msg = "商品數量最少為 1，此次更動無效。";
            $type = "error";
        }

        return response()->json(['msg' => $msg, 'type' => $type], 201);
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
        $type = "warning";
        return response()->json(['msg' => $msg, 'type' => $type], 201);
    }
    public function deleteAllItemsFromCart()
    {
        $exist = Cart::where('user_id', $this->user_id)->exists();
        if (!$exist) {
            $msg = "購物車中沒有任何商品，此次操作無效";
            return response()->json(['msg' => $msg], 404);
        }

        Cart::where('user_id', $this->user_id)->delete();
        // 回傳訊息
        $msg = "已經清空您的購物車";
        $type = "warning";
        return response()->json(['msg' => $msg, 'type' => $type], 201);
    }
}
