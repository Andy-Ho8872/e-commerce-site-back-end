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
    public function increaseByOne(CartService $service)
    {
        return $service->increaseQuantityByOne();
    }
    //? 數量 - 1
    public function decreaseByOne(CartService $service)
    {
        return $service->decreaseQuantityByOne();
    }
//* 刪除
    //? 移除購物車內的商品 (單筆)
    public function destroy(CartService $service)
    {
        return $service->deleteItemFromCart();
    }
    //? 清空購物車
    public function destroyAll(CartService $service)
    {
        return $service->deleteAllItemsFromCart();
    }
}
