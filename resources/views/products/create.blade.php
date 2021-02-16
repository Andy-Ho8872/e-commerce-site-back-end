@extends('layouts.app')

@section('content')
<div class="xl:w-8/12 mx-auto">
    <form class="py-4" action="/products/create" method="POST">
        <h1 class="text-center text-2xl font-sans font-bold tracking-widest">產品上架後台</h1>
        @csrf
        <div class="bg-green-200 shadow-lg rounded-3xl px-8 py-8 flex flex-col mx-auto">
            <!-- 名稱 -->
            <div class="mb-4">
                <label class="labelText" for="title">
                    商品名稱
                </label>
                <input class="input-shadow text-grey-darker input-focus-blue" name="title" type="text" placeholder="優質手機">
            </div>
            <!-- 敘述 -->
            <div class="mb-4">
                <label class="labelText" for="description">
                    商品敘述
                </label>
                <textarea rows="5" class="input-shadow text-grey-darker mb-3 input-focus-blue" name="description" type="text" placeholder="Lorem...."></textarea>
            </div>
            <!-- 圖片 -->
            <div class="mb-4">
                <label class="labelText" for="imgUrl">
                    商品圖片網址
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="imgUrl" type="text" placeholder="http://.....">
            </div>
            <!-- 單價 -->
            <div class="mb-4">
                <label class="labelText" for="unit_price">
                    商品單價
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="unit_price" type="number" placeholder="4899">
            </div>
            <!-- 數量 -->
            <div class="mb-4">
                <label class="labelText" for="stock_quantity">
                    存貨數量
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="stock_quantity" type="number" placeholder="100">
            </div>
            <!-- 折價率 -->
            <div class="mb-4">
                <label class="labelText" for="discount_rate input-focus-blue">
                    商品折價率
                </label>
                <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="discount_rate" type="number" step="0.01" placeholder="1.00">
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
            </div>
            <!-- 標籤選擇 -->
            <fieldset class="mb-4">
                <label class="labelText">產品標籤</label>
                @foreach($tags as $tag)
                <label class="mx-3 px-3 py-1 bg-white rounded-2xl font-semibold">
                    <input name="tags[]" type="checkbox" value="{{ $tag->id }}" class="mb-5">
                    {{ $tag->title }}
                </label>
                @endforeach
            </fieldset>
            <!-- 按鈕操作 -->
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="border-2 rounded-full font-bold text-white p-4 px-8 m-6 transition-300-ease-in-out bg-red-500 hover:bg-red-700">
                    取消並返回
                </a>
                <button type="submit" class="border-2 rounded-full font-bold text-white p-4 m-6 transition-300-ease-in-out bg-blue-500 hover:bg-blue-700">
                    確認上架
                </button>
            </div>
        </div>
    </form>
</div>
@endsection('content')