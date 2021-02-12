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
        // (預載入查詢指定的關聯) 
        $products = Product::with('tags')->get();

        // 回傳結果
        return response()->json(['products' => $products], 200);
    }

    // 取得首頁的產品  
    public function indexPageProducts()
    {   
        $products = Product::with('tags')->take(5)->get();

        return response()->json(['products' => $products], 200);
    }

    // 圖片輪播的商品 (10 個)
    public function carousel()
    {
        $products = Product::with('tags')->take(10)->get();

        return response()->json(['products' => $products], 200);
    }

    // 商品換頁 (pagination) 
    public function paginate()
    {
        // 目前為 一頁有 10 個商品
        $products = Product::with('tags')->paginate(10);

        return response()->json(['products' => $products], 200);
    }


// 後台部分 --------------------------------------------------------Start
    // 上架產品
    public function store(Request $request)
    {
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'unit_price' => $request->unit_price,
            'imgUrl' => $request->imgUrl,
            'stock_quantity' => $request->stock_quantity,
            'available' => $request->available,
            'discount_rate' => $request->discount_rate,
        ]);

        // 產品標籤關聯
        $product->tags()->sync($request->tags, false);

        // redirect to home page
        return redirect('/');
    }
    public function getTags()
    {
        $tags = Tag::all();

        return view('products.create', ['tags' => $tags]);
    }
    public function products() 
    {
        $products = Product::all();

        return view('products.checkout' , ['products' => $products]);
    }
    public function showById($id) 
    {
        $product = Product::findOrFail($id);
        $tags = Tag::all();

        return view('products.show' , ['product' => $product, 'tags' => $tags]);
    }
    // 更新產品資料
    public function edit(Request $request, $id) 
    {
        $product = Product::findOrFail($id);

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'unit_price' => $request->unit_price,
            'imgUrl' => $request->imgUrl,
            'stock_quantity' => $request->stock_quantity,
            'available' => $request->available,
            'discount_rate' => $request->discount_rate,
        ]);

        // 產品標籤關聯
        $product->tags()->sync($request->tags);

        return redirect('/products/checkout');
    }
// 後台部分 --------------------------------------------------------End


    // 顯示單一商品
    public function show($id)
    {
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
