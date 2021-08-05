<?php

namespace App\Http\Controllers;

//* Facades
use Illuminate\Support\Facades\DB;
//* Requests
use App\Http\Requests\OrderRequest;
//* Service 
use App\Services\OrderService;

class OrderController extends Controller
{ 
//* 建立訂單
    public function createOrder(OrderRequest $request, OrderService $service)
    {
        return $service->createOrderAndSendNotification($request);
    }

//* 撈取訂單
    //? 所有訂單
    public function getAllOrders(OrderService $service)
    {
        return $service->getUserOrders();
    }
    //? 單筆訂單
    public function getSingleOrder(OrderService $service, $order_id)
    {
        return $service->getUserSingleOrder($order_id);
    }

//* 刪除訂單
    public function deleteOrder(OrderService $service, $order_id)
    {
        return $service->deleteSingleOrder($order_id);
    }

//* 前端表單資料
    public function getTableColumns()
    {
        $payments = DB::table('payments')->get();
        $status = DB::table("status")->get();

        return response()->json(['payments' => $payments, 'status' => $status], 200);
    }
}
