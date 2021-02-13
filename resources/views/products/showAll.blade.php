@extends('layouts.app')

@section('content')
<h1 class="text-center bold text-2xl pt-4">產品資訊</h1>
<!-- <div class="text-center">
    <a href="/">
        <button class="border-2 rounded-full font-bold text-white p-4 m-6 transition-300-ease-in-out bg-purple-500 hover:bg-purple-700">
            回到首頁
        </button>
    </a>
</div>


<div class="bg-gray-200">

    <div class="flex flex-wrap items-center justify-center p-6">
        @foreach($products as $product)
        <a href="/products/show/{{ $product->id }}">
            <div class="bg-white flex items-center justify-center rounded-lg m-3 p-3 w-52 h-20">
                {{ $product->id }} - {{ $product->title }}
            </div>
        </a>
        @endforeach
    </div>
</div> -->

<div class="bg-gray-100 rounded-sm">
    <table class="table-auto bg-white w-8/12 mx-auto border-collapse rounded-sm">
        <thead class="bg-pink-500 text-white">
            <tr>
                <th class="w-1/12 p-4">編號</th>
                <th class="w-3/12 p-4">名稱</th>
                <th class="w-3/12 p-4">敘述</th>
                <th class="w-3/12 p-4">單價</th>
                <th class="w-3/12 p-4">操作</th>
            </tr>
        </thead>
        <tbody class="border border-gray-300">
            @foreach($products as $product)
            <tr class="mt-2 text-center  even:bg-gray-100">
                <!-- 產品編號 -->
                <td class="w-1/12 p-4">{{ $product->id }}</td>
                <!-- 產品名稱 -->
                <td class="w-3/12 p-4">{{ $product->title }}</td>
                <!-- 產品敘述 -->
                <td class="w-3/12 p-4">{{ Str::limit($product->description, 80, $end='...') }}</td>
                <!-- 產品單價 -->
                <td class="w-3/12 p-4">{{ $product->unit_price }}</td>
                <!-- 操作按鈕 -->
                <td>
                    <button class="rounded-full bg-green-500 hover:bg-green-600 px-4 py-2 text-white outline-none m-2">
                        查看
                    </button>
                    <button class="rounded-full bg-red-400 hover:bg-red-600 px-4 py-2 text-white outline-none m-2">
                        編輯
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection