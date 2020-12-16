<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// 使用 Model
use App\Models\Product;
use App\Models\Tag;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all products
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // get sepecific product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        // 取得標籤名稱
        $tags = Product::find($id)->tag->title; 
        
        return response()->json(['product' => $product, 'tags' => $tags]);
    }
    
    public function showByTag($id) 
    {
        // 取得符合 tag_id 的商品
        $product = Tag::find($id)->product;

        // 取得標籤名稱
        $tags = Product::find($id)->tag->title;

        return response()->json(['tags' => $tags, 'product' => $product ]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $product = Product::find($id);
        $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return Product::destroy($id);
    }
}
