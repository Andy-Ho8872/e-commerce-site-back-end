@extends('layouts.app')

@section('content')

<form method="POST">
    @csrf
    @method('PATCH')
    <div class="bg-gray-200 shadow-md rounded px-8 pt-6 pb-8 flex flex-col w-6/12 mx-auto">
        <!-- 名稱 -->
        <div class="mb-4">
            <label class="labelText" for="title">
                商品名稱
            </label>
            <input class="input-shadow text-grey-darker input-focus-blue" name="title" type="text" value="{{ $product->title }}">
        </div>

        <!-- 敘述 -->
        <div class="mb-4">
            <label class="labelText" for="description">
                商品敘述
            </label>
            <textarea rows="5" class="input-shadow text-grey-darker mb-3 input-focus-blue" name="description" type="text">
            {{ $product->description }}
            </textarea>
        </div>

        <!-- 圖片 -->
        <div class="mb-4">
            <label class="labelText" for="imgUrl">
                商品圖片網址
            </label>
            <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="imgUrl" type="text" value="{{ $product->imgUrl }}">
        </div>

        <!-- 單價 -->
        <div class="mb-4">
            <label class="labelText" for="unit_price">
                商品單價
            </label>
            <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="unit_price" type="number" value="{{ $product->unit_price }}">
        </div>

        <!-- 數量 -->
        <div class="mb-4">
            <label class="labelText" for="stock_quantity">
                存貨數量
            </label>
            <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="stock_quantity" type="number" value="{{ $product->stock_quantity }}">
        </div>

        <!-- 折價率 -->
        <div class="mb-4">
            <label class="labelText" for="discount_rate input-focus-blue">
                商品折價率
            </label>
            <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="discount_rate" type="number" step="0.01"">
        </div>

        <!-- 是否有現貨 -->
        <div class="mb-4">
            <label class="labelText" for="available">
                是否有貨
            </label>
            <select name="available">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>
            <p>{{ $product->available }}</p>
        </div>

        <!-- 標籤選擇 -->
        <fieldset class="mb-4">
            <label class="labelText">產品標籤</label>
            @foreach($tags as $tag)
            <div>
                <input name="tags[]" type="checkbox" value="{{ $tag->id }}">
                <label for="{{ $tag->title }}">{{ $tag->title }}</label>
            </div>
            @endforeach
        </fieldset>

        <!-- 上架按鈕 -->
        <button type="submit" class="border-2 rounded-full font-bold text-white p-4 m-6 transition-300-ease-in-out bg-green-500 hover:bg-green-700">
            確認變更
        </button>
    </div>
</form>
<div class="w-48 text-center border-2 rounded-full font-bold text-white mt-4 p-4 mx-auto transition-300-ease-in-out bg-blue-500 hover:bg-blue-700">
    <!-- 返回 -->
    <a href="/">
        <button>
            返回首頁
        </button>
    </a>
</div>

@endsection