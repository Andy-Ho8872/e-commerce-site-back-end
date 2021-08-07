<?php

namespace App\Http\Controllers;

//* Models
use App\Models\Product;
use App\Models\Tag;

//* Facades 
use Illuminate\Support\Facades\Cache;

//* Requests 
use App\Http\Requests\StoreAndEditRequest;

//* Services 
use App\Services\ProductService;

class ProductController extends Controller
{
    // API 的部分 -----------------------------------------------------------------------------------Start
    //* 所有的產品
    public function index(ProductService $service)
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
    public function paginate(ProductService $service)
    {
        return $service->getPaginatedProducts();
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
    public function store(StoreAndEditRequest $request)
    {
        // 新增產品 
        $product = Product::create($request->validated());
        // 產品標籤關聯
        $product->tags()->sync($request->tags, false);
        // 提示訊息
        $message = "商品上架成功";
        // 重新導向
        return redirect()->route('products.index')->with('message', $message);
    }
    public function getTags()
    {
        $tags = Tag::all();

        return view('products.create', ['tags' => $tags]);
    }
    public function products()
    {
        $products = Product::all();

        return view('products.showAll', ['products' => $products]);
    }
    public function showById($product_id)
    {
        $product = Product::findOrFail($product_id);
        $tags = $product->tags;

        return view('products.show', ['product' => $product, 'tags' => $tags]);
    }
    // 更新產品資料
    public function edit(StoreAndEditRequest $request, $product_id)
    {
        // 先取得該產品
        $product = Product::findOrFail($product_id);
        // 更新產品
        $product->update($request->validated());
        // 產品標籤關聯
        $product->tags()->sync($request->tags);
        // 提示訊息
        $message = "已經成功變更，請查閱。";
        // 重新導向至該產品
        return redirect()->route('products.show', ['product_id' => $product_id])->with('message', $message);
    }
    public function editPage($product_id)
    {
        $product = Product::findOrFail($product_id);

        $tags = Tag::all();

        return view('products.edit', ['product' => $product, 'tags' => $tags]);
    }
    // 後台部分 -----------------------------------------------------------------------------------End
}
