@extends('layouts.app')

@section('content')
<h1 class="text-center bold text-2xl pt-4">產品資訊</h1>
<div class="text-center">
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
</div>
@endsection