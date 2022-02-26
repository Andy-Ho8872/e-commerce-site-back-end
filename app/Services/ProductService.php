<?php

namespace App\Services;

//* Models
use App\Models\Product;
use App\Models\Tag;

//* Facades
use Illuminate\Support\Facades\Cache;

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
        //* 熱門商品 
        $hot_sale_products = Product::query()
            ->with('tags')
            ->orderBy('id', 'desc') //* 可按照需求填入想取得的資料 e.g 評級最高、最熱賣、上架時間 等等...
            ->take(12)
            ->get();
        //* 限時特賣的商品 
        $flash_sale_products = Product::query()
            ->orderBy('rating', 'desc')
            ->take(10)
            ->select(
                'id',
                'title',
                'imgUrl',
                'discount_rate',
            )
            ->get();
        //* 最新上架的商品 
        $latest_products = Product::query()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->select(
                'id',
                'title',
                'imgUrl',
                'discount_rate',
                'created_at'
            )
            ->get();
        return response()->json([
            'hot_sale_products' => $hot_sale_products,
            'flash_sale_products' => $flash_sale_products,
            'latest_products' => $latest_products,
            'success' => true,
        ], 200);
    }

    //* 商品的分頁功能 
    public function getPaginatedProducts($orderBy, $sortBy)
    {
        //* 目前為一頁有 12 個商品
        $currentPage = request()->input('page', 1); //* 當前頁數
        //* 使用 Cache 
        $products = Cache::remember("pagination-${currentPage}-${orderBy}-${sortBy}", 60 * 2, function () use($orderBy, $sortBy) {
            return Product::query()
                ->with('tags')
                ->orderBy($orderBy, $sortBy)
                ->paginate(12);
        });
        return response()->json(['products' => $products], 200);
    }

    //* 顯示單一商品
    public function getSingleProduct($id)
    {
        $product = Product::query()
                ->with(['tags', 'variations'])
                ->findOrFail($id);
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
