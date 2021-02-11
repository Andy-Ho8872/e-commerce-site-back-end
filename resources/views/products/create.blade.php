@extends('layouts.app')


@section('content')
<h1 class="italic text-center mt-8">產品上架後台</h1>
<form action="/products/create" method="POST">
    @csrf
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">

        <div class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="title">
                商品名稱
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" name="title" type="text" placeholder="優質手機">
        </div>

        <div class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="description">
                商品敘述
            </label>
            <textarea class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" name="description" type="text" placeholder="Lorem...."></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="imgUrl">
                商品圖片網址
            </label>
            <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" name="imgUrl" type="text" placeholder="http://.....">
        </div>

        <div class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="unit_price">
                商品單價
            </label>
            <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" name="unit_price" type="number" placeholder="4899">
        </div>

        <div class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="stock_quantity">
                存貨數量
            </label>
            <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" name="stock_quantity" type="number" placeholder="100">
        </div>

        <div class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="discount_rate">
                商品折價率
            </label>
            <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" name="discount_rate" type="number" step="0.01" placeholder="1.00">
        </div>

        <div class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="available">
                是否有貨
            </label>
            <select name="available">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>
        </div>

        <fieldset class="mb-4">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="tags">
                產品標籤
            </label>
            @foreach($tags as $tag)
                <div>
                    <input name="tags[]" type="checkbox" value="{{ $tag->id }}">
                    <span>{{ $tag->title }}</span>    
                </div>
            @endforeach 
        </fieldset>

        <button type="submit" class="border-2 border-blue-500 rounded-full font-bold text-blue-500 px-4 py-3 transition duration-300 ease-in-out hover:bg-blue-500 hover:text-white mr-6">
            上架
        </button>

    </div>
</form>
@endsection('content')