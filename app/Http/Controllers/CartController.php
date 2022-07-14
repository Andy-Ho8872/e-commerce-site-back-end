<?php

namespace App\Http\Controllers;

//* 驗證規則
use App\Http\Requests\CartRequest;

// Services
use App\Services\CartService;

class CartController extends Controller
{
//* 查詢
    //* 取得該使用者(id)的購物車 
    public function show(CartService $service)
    {
        return $service->showUserCart();
    }
//* 新增
    //? 新增商品至購物車
    public function create(CartRequest $request, CartService $service)
    {
        return $service->addItemToCart($request);
    }
//* 修改
    //? 修改購物車內商品數量
    public function update(CartRequest $request, CartService $service)
    {
        return $service->updateQuantityByInput($request);
    }
    //? 數量 + 1
    public function increaseByOne(CartService $service, $cart_id)
    {
        return $service->increaseQuantityByOne($cart_id);
    }
    //? 數量 - 1
    public function decreaseByOne(CartService $service, $cart_id)
    {
        return $service->decreaseQuantityByOne($cart_id);
    }
//* 刪除
    //? 移除購物車內的商品 (單筆)
    public function destroy(CartService $service, $cart_id)
    {
        return $service->deleteItemFromCart($cart_id);
    }
    //? 清空購物車
    public function destroyAll(CartService $service)
    {
        return $service->deleteAllItemsFromCart();
    }
}
