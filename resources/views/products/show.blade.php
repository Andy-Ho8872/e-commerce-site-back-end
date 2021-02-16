@extends('layouts.app')

@section('content')
<div class="xl:w-8/12 mx-auto">
    <form class="py-4" method="POST">
        <h1 class="text-center text-2xl font-sans font-bold tracking-widest">商品資訊-編號-{{ $product->id }}</h1>
        <div class="bg-blue-200 shadow-lg rounded-3xl px-8 py-8 flex flex-col mx-auto">
            <!-- 名稱 -->
            <div class="mb-4">
                <label class="labelText" for="title">
                    商品名稱
                </label>
                <input class="input-shadow text-grey-darker input-focus-blue" name="title" type="text" value="{{ $product->title }}" readonly>
            </div>
            <!-- 敘述 -->
            <div class="mb-4">
                <label class="labelText" for="description">
                    商品敘述
                </label>
                <textarea rows="5" class="input-shadow text-grey-darker mb-3 input-focus-blue" name="description" type="text" readonly>
                {{ $product->description }}
                </textarea>
            </div>
            <!-- 圖片 -->
            <div class="mb-4">
                <label class="labelText" for="imgUrl">
                    商品圖片網址
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="imgUrl" type="text" value="{{ $product->imgUrl }}" readonly>
            </div>
            <!-- 單價 -->
            <div class="mb-4">
                <label class="labelText" for="unit_price">
                    商品單價
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="unit_price" type="number" value="{{ $product->unit_price }}" readonly>
            </div>
            <!-- 數量 -->
            <div class="mb-4">
                <label class="labelText" for="stock_quantity">
                    存貨數量
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="stock_quantity" type="number" value="{{ $product->stock_quantity }}" readonly>
            </div>
            <!-- 折價率 -->
            <div class="mb-4">
                <label class="labelText" for="discount_rate input-focus-blue">
                    商品折價率
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="discount_rate" type="number" step="0.01" value="{{ $product->discount_rate }}" readonly>
            </div>
            <!-- 是否有現貨 -->
            <div class="mb-4">
                <label class="labelText" for="available">
                    是否有貨
                </label>
                <select name="available" disabled>
                    <option value="1" @if($product->available == 1) selected @endif>是</option>
                    <option value="0" @if($product->available == 0) selected @endif>否</option>
                </select>
            </div>
            <!-- 標籤選擇 -->
            <fieldset class="mb-4">
                <label class="labelText pb-4">產品標籤</label>
                @foreach($tags as $tag)
                <span class="mx-2 p-3 bg-pink-500 text-white font-bold tracking-widest rounded-full">{{ $tag->title }}</span>
                @endforeach
            </fieldset>
            <!-- 操作按鈕 -->
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="border-2 rounded-full font-bold text-white p-4 px-8 m-6 transition-300-ease-in-out bg-green-500 hover:bg-green-700">
                    返回
                </a>
                <a href="{{ route('products.edit', $product->id) }}" class="border-2 rounded-full font-bold text-white p-4 m-6 transition-300-ease-in-out bg-red-500 hover:bg-red-700">
                    變更內容
                </a>
            </div>
        </div>
    </form>
</div>
@endsection