<?php

namespace App\Http\Controllers;

//* Models
use App\Models\Tag;
use App\Models\Product;
use App\Models\Variation;

//* Requests 
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Http\Requests\ProductRequest;

class AdminController extends Controller
{   
    public function showProducts()
    {
        $products = Product::with(['tags', 'variations'])->get();

        return response()->json(['products' => $products]);
    }

    public function showProduct($product_id)
    {
        $product = Product::with(['tags', 'variations'])->find($product_id);

        return response()->json(['product' => $product]);
    }

    public function storeProduct(ProductRequest $request)
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
        return response()->json(['message' => $message], 201);
    }

    public function updateProduct(ProductRequest $request, $product_id)
    {
        //* 先取得該產品
        $product = Product::findOrFail($product_id);
        //* 更新產品資料
        $product->update($request->validated());
        //* 產品標籤關聯
        $product->tags()->sync($request->tags);
        //* 更新產品規格
        $variations = $request->variations_title;
        $product->variations()->sync($variations);

        if($variations) {
            foreach ($variations as $key => $variation) {
                // 更新資料
                Variation::query()
                    ->where('product_id', $product_id)
                    ->update([
                        'title' => $request->variation_title[$key],
                        'options' => $request->input("variation_options_${key}")
                    ]);
            }
        }
        // 提示訊息
        $message = "產品資訊已經成功變更，請查閱。";
        
        return response()->json(['message' => $message], 201);
    }

    public function createProductVariation(Request $request)
    {
        Variation::create([
            'product_id' => $request->product_id,
            'title' => $request->variation_title,
            'options' => $request->input("variation_options")
        ]);

        $message = "規格新增成功";

        return response()->json(['message' => $message], 201);
    }

    public function deleteProductVariation($product_id, $variation_id)
    {
        Variation::where('product_id', $product_id)->where('id', $variation_id)->delete();
        // 提示訊息
        $message = "已經刪除該規格，請查閱。";

        return response()->json(['message' => $message], 201);
    }

    public function deleteProductVariationOption(Request $request, $product_id, $variation_id)
    {
        Variation::where('product_id', $product_id)
        ->where('id', $variation_id)
        ->update($request->only(['variation_options']));
        // 提示訊息
        $message = "已經刪除(變更)該選項，請查閱。";

        return response()->json(['message' => $message], 201);
    }

    //* -----------------------------以下為產品標籤 CRUD-------------------------------------------------
    public function showProductTags()
    {
        $tags = Tag::get();

        return response()->json(['tags' => $tags]);
    }

    public function showProductTag($tag_id)
    {
        $tag = Tag::find($tag_id);

        return response()->json(['tag' => $tag]);
    }

    public function storeProductTag(TagRequest $request)
    {
        Tag::create($request->validated());
        // 提示訊息
        $message = "標籤新增成功";

        return response()->json(['message' => $message]);
    }

    public function updateProductTag(TagRequest $request, $tag_id)
    {
        $tag = Tag::find($tag_id);

        $tag->update($request->validated());

        $message = "標籤變更成功";

        return response()->json(['message' => $message]);
    }
}
