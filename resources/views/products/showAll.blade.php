@extends('layouts.app')

@section('content')
<h1 class="text-center font-bold text-4xl py-4">產品資訊</h1>
<!-- 返回按鈕 -->
<div class="text-center m-8">
    <a href="{{ route('home') }}" class="border-2 rounded-full font-bold text-white p-4 px-8 m-6 transition-300-ease-in-out bg-blue-500 hover:bg-blue-700">
        返回首頁
    </a>
</div>
<!-- 表格內容 -->
<div class="shadow-lg bg-gray-100 mb-12 rounded-3xl overflow-auto xl:w-8/12 mx-auto">
    <table class="table-auto bg-white mx-auto border-collapse">
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
                    <a href="/products/show/{{ $product->id }}">
                        <button class="rounded-full bg-green-500 hover:bg-green-600 px-4 py-2 text-white outline-none m-2">
                            查看
                        </button>
                    </a>
                    <a href="/products/edit/{{ $product->id }}">
                        <button class="rounded-full bg-red-400 hover:bg-red-600 px-4 py-2 text-white outline-none m-2">
                            編輯
                        </button>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection