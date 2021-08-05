<?php

namespace App\Services;

//* Models
use App\Models\Product;
use App\Models\Tag;

//* Facades
use Illuminate\Support\Facades\Cache;

//* Requests 
use App\Http\Requests\StoreAndEditRequest;

class ProductService
{
    //* 取得商品的標籤 
    public function getProductTags()
    {
        $tags = Tag::get();

        return response()->json(['tags' => $tags], 200);
    }

    //* 取得所有商品(測試API用) 
    public function getAllProducts()
    {
        //* 預載入查詢指定的關聯
        $products = Product::query()
            ->with('tags')
            ->get();

        // 回傳結果
        return response()->json(['products' => $products], 200);
    }

    //* 取得首頁的產品 
    public function index()
    {
        $products = Cache::remember('index', 60 * 3, function () {
            return Product::query()
                ->with('tags')
                // ->orderBy(condition, sortMethod) //* 可按照需求填入想取得的資料 e.g 評級最高、最熱賣、上架時間 等等...
                ->take(6)
                ->get();
        });
        return response()->json(['products' => $products], 200);
    }

    //* 取得輪播的產品
    public function getCarouselProducts()
    {
        //* 限時特賣的商品 
        $flash_sale_products = Cache::remember('flash_sale_products', 60 * 3, function () {
            return Product::query()
                ->orderBy('rating', 'desc')
                ->take(10)
                ->select(
                    'id',
                    'title',
                    'imgUrl',
                    'discount_rate',
                )
                ->get();
        });

        //* 最新上架的商品 
        $latest_products = Cache::remember('latest_products', 60 * 3, function () {
            return Product::query()
                ->orderBy('id', 'desc')
                ->take(10)
                ->select(
                    'id',
                    'title',
                    'imgUrl',
                    'discount_rate',
                    'created_at'
                )
                ->get();
        });
        return response()->json(['flash_sale_products' => $flash_sale_products, 'latest_products' => $latest_products], 200);
    }

    //* 商品的分頁功能 
    public function getPaginatedProducts()
    {
        //* 目前為一頁有 12 個商品
        $currentPage = request()->get('page', 1); //* 當前頁數
        //* 使用 Cache 
        $products = Cache::remember("pagination-${currentPage}", 60 * 2, function () {
            return Product::query()
                ->with('tags')
                ->paginate(12);
        });
        return response()->json(['products' => $products], 200);
    }

    //* 顯示單一商品
    public function getSingleProduct($id)
    {
        $product = Cache::remember("product-${id}", 60 * 2, function () use ($id) {
            return Product::query()
                ->with('tags')
                ->findOrFail($id);
        });
        return response()->json(['product' => $product], 200);
    }

    //* 藉由商品標籤取的商品
    public function getProductsByTags($id)
    {
        $tag = Cache::remember("tag-${id}", 60 * 2, function () use ($id) {
            return Tag::query()
                ->with('products')
                ->findOrFail($id);
        });
        return response()->json(['tag' => $tag], 200);
    }

    //* 搜尋商品(含分頁)
    public function searchProductsWithPagination($search)
    {
        //* 當前的頁數 
        $currentPage = request()->get('page', 1);
        //* 使用快取 
        $products = Cache::remember("searchingFor-${search}-${currentPage}", 60 * 2, function () use ($search) {
            return Product::query()
                ->with('tags')
                ->where('title', 'LIKE', "%{$search}%")
                ->orwhereHas('tags', function ($query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%");
                })->paginate(12);
        });
        //* 提示訊息 
        $msg = "關於{$search}的搜尋結果";
        //* 若查無結果
        if (!count($products)) {
            $msg = "找不到關於{$search}的搜尋結果";
            return response()->json(['msg' => $msg]);
        }
        return response()->json(['msg' => $msg, 'products' => $products], 200);
    }

    //* 搜尋自動補全
    public function getAutoComplete($search)
    {
        $products = Cache::remember("searchAutoComplete-${search}", 60 * 2, function () use ($search) {
            return Product::query()
                ->where('title', 'LIKE', "%{$search}%")
                ->orwhereHas('tags', function ($query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%");
                })
                ->take(8)
                ->select(
                    'id',
                    'title',
                    'imgUrl',
                )
                ->get();
        });
        //* 提示訊息 
        $msg = "關於{$search}的搜尋結果";
        //* 若查無結果
        if (!count($products)) {
            $msg = "找不到關於{$search}的搜尋結果";
            return response()->json(['msg' => $msg]);
        }
        return response()->json(['msg' => $msg, 'products' => $products], 200);
    }
}
