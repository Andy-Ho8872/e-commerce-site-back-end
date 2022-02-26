<?php

namespace App\Http\Controllers;

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
}