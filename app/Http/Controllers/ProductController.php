<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// 使用 Model
use App\Models\Product;
use App\Models\Tag;

class ProductController extends Controller
{
    public function index()
    {   
        // $product = Product::pluck('title'); // 只取得單一欄位的資訊
        // $product = Product::limit(5)->get(); // 只取得 x 筆資料
        
        // $product = Product::all()->count(); // 計算總共多少筆資料
        // $product = Product::all()->max('unit_price'); // 計算該欄位的值

        // $product = Product::where('id', 5)->exists(); // 判斷是否存在 回傳布林值
        
        // $product = Product::select('id','title')->get(); // 選取特定(可以複數) column 的資訊
        // $product = Product::select('title')->addSelect('unit_price')->get(); // 增加選取的量

        // 取得所有產品
        $product = Product::all();
        
        
        return response()->json(['product' => $product]);
    }


    public function store(Request $request)
    {
        // use Product Model
        $product = new Product();

        //get data from form
        $product->title = request('title'); // 寫法 1
        $product->description = $request->description; // 寫法 2
        $product->unit_price = $request->unit_price;
        $product->imgUrl = $request->imgUrl;
        $product->stock_quantity = $request->stock_quantity;
        $product->available = $request->available;
        $product->tag_id = $request->tag_id;

        // save the data
        $product->save();

        // redirect to home page
        return redirect('/');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        // 取得標籤名稱
        // $tags = Product::findOrFail($id)->tag->title; 
        $product->tag->title; 
        
        return response()->json(['product' => $product]);
    }
    

    public function showByTag($id) 
    {
        // 取得符合 tag_id 的商品
        $product = Tag::find($id)->products;

        // 取得標籤名稱
        $tags = Tag::find($id);

        return response()->json(['tags' => $tags, 'product' => $product ]);
    }


    public function search($title) 
    {
        // 搜尋商品
        $product = Product::where('title', 'LIKE', '%'.$title.'%')->get();
        
        return response()->json(['product' => $product]);
    }


    public function update(Request $request, $id)
    {
        //
        $product = Product::find($id);
        $product->update($request->all());
        return $product;
    }

    
    public function destroy($id)
    {
        //
        return Product::destroy($id);
    }
}
