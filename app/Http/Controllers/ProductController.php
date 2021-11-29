<?php

namespace App\Http\Controllers;

//* Models
use App\Models\Product;
use App\Models\Tag;

//* Requests 
use App\Http\Requests\ProductRequest;
use App\Models\Variation;
//* Services 
use App\Services\ProductService;

class ProductController extends Controller
{
    // API 的部分 -----------------------------------------------------------------------------------Start
    //* 所有的產品
    public function test(ProductService $service)
    {
        return $service->getAllProducts();
    }
    //* 取得首頁的產品  
    public function indexPageProducts(ProductService $service)
    {
        return $service->index();
    }
    //* 圖片輪播的商品 (10 個)
    public function carousel(ProductService $service)
    {
        return $service->getCarouselProducts();
    }
    //* 商品標籤
    public function productTags(ProductService $service)
    {
        return $service->getProductTags();
    }
    //* 商品換頁 (pagination) 
    public function paginate($orderBy, $sortBy, ProductService $service)
    {
        return $service->getPaginatedProducts($orderBy, $sortBy);
    }
    //* 顯示單一商品
    public function show($id, ProductService $service)
    {
        return $service->getSingleProduct($id);
    }
    //* 藉由商品標籤顯示
    public function showByTag($id, ProductService $service)
    {
        return $service->getProductsByTags($id);
    }
    //* 搜尋商品(含分頁)
    public function searchWithPagination($search, ProductService $service)
    {
        return $service->searchProductsWithPagination($search);
    }
    //* 搜尋欄自動補全
    public function searchAutoComplete($search, ProductService $service)
    {
        return $service->getAutoComplete($search);
    }
    // API 的部分 -----------------------------------------------------------------------------------End


    // 後台部分 -----------------------------------------------------------------------------------Start
    // 上架產品
    public function store(ProductRequest $request)
    {
        // 新增產品 
        $product = Product::create($request->validated());
        // 產品標籤關聯
        $product->tags()->sync($request->tags, false);
        // 產品規格關聯
        if ($request->has('variation_title')) {
            foreach ($request->variation_title as $key => $variation) {
                Variation::create([
                    'product_id' => $product->id,
                    'title' => $request->variation_title,
                    'options' => $request->input("variation_options_${key}")
                ]);
            }
        }
        // 提示訊息
        $message = "商品上架成功";
        // 重新導向
        return redirect()->route('products.index')->with('message', $message);
    }
    public function create()
    {
        $tags = Tag::all();

        return view('products.create', ['tags' => $tags]);
    }
    public function index()
    {
        $products = Product::all();

        return view('products.index', ['products' => $products]);
    }
    public function showById($product_id)
    {
        $product = Product::with(['tags', 'variations'])->findOrFail($product_id);

        return view('products.show', ['product' => $product]);
    }
    // 更新產品資料
    public function update(ProductRequest $request, $product_id)
    {
        // 先取得該產品
        $product = Product::findOrFail($product_id);
        // 更新產品
        $product->update($request->validated());
        // 產品標籤關聯
        $product->tags()->sync($request->tags);
        // 更新產品規格名稱
        $variations = Variation::where('product_id', $product_id)->get();
        foreach ($variations as $key => $variation) {
            // 更新資料
            Variation::query()
                ->where('product_id', $product_id)
                ->where('id', $variation->id)
                ->update([
                    'title' => $request->variation_title[$key],
                    'options' => $request->input("variation_options_${key}")
                ]);
        }
        // 提示訊息
        $message = "已經成功變更，請查閱。";
        // 重新導向至該產品
        return redirect()->route('products.show', ['product_id' => $product_id])->with('message', $message);
    }
    public function deleteVariation($product_id)
    {
        Variation::where('product_id', $product_id)->delete();
        // 提示訊息
        $message = "已經刪除該規格，請查閱。";

        return redirect()->route('products.show', ['product_id' => $product_id])->with('message', $message);
    }
    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id);

        $tags = Tag::all();

        return view('products.edit', ['product' => $product, 'tags' => $tags]);
    }
    // 後台部分 -----------------------------------------------------------------------------------End
}
