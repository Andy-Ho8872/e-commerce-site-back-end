<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;
// 使用到的 Model
use App\Models\Product;
use App\Models\Tag;

class ProductController extends Controller
{
    // 所有的產品
    public function index()
    {   
        // 取得所有產品 (預載入查詢指定的關聯) 
        $products = Product::with('tags')->get();

        // 回傳結果
        return response()->json(['products' => $products], 200);
    }

    // 圖片輪播的商品 (10 個)
    public function carousel()
    {
        $products = Product::take(10)->get();

        return response()->json(['products' => $products], 200);
    }

    // 商品換頁 (pagination) 
    public function paginate()
    {
        // 目前為 一頁有 5 個商品
        $products = Product::with('tags')->paginate(5);

        return response()->json(['products' => $products], 200);
    }


    // 上架產品 (暫時不用)
    // public function store(Request $request)
    // {
    //     $product = new Product();

    //     //get data from form
    //     $product->title = request('title'); // 寫法 1
    //     $product->description = $request->description; // 寫法 2
    //     $product->unit_price = $request->unit_price;
    //     $product->imgUrl = $request->imgUrl;
    //     $product->stock_quantity = $request->stock_quantity;
    //     $product->available = $request->available;
    //     $product->tag_id = $request->tag_id;

    //     // save the data
    //     $product->save();

    //     // redirect to home page
    //     return redirect('/');
    // }




    // 顯示單一商品
    public function show($id)
    {
        // $product = Product::with('tags')->findOrFail($id); // 簡略寫法

        // 取得該產品資訊
        $product = Product::findOrFail($id);

        // 取得該產品的所有標籤(有可能是複數個) 
        $product->tags; 

        return response()->json(['product' => $product], 200);
    }
    

    // 藉由商品標籤顯示
    public function showByTag($id) 
    {
        // 取得標籤名稱
        $tags = Tag::findOrFail($id);

        // 取得符合 tag_id 的商品
        $products = Tag::findOrFail($id)->products;

        return response()->json(['tags' => $tags, 'products' => $products], 200);
    }


    // 搜尋商品
    public function search($title) 
    {
        $products = Product::where('title', 'LIKE', "%{$title}%")->with('tags')->get();
        
        $msg = "關於{$title}的搜尋結果";

        return response()->json(['msg' => $msg, 'products' => $products], 200);
    }
}
