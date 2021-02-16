@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('products.create') }}">
        <button class="outline-none border-2 rounded-full font-bold text-white p-4 m-6 transition-300-ease-in-out bg-red-500 hover:bg-red-700">
            點我進行產品上架
        </button>
    </a>
    <a href="{{ route('products.index') }}">
        <button class="border-2 rounded-full font-bold text-white p-4 m-6 transition-300-ease-in-out bg-red-500 hover:bg-red-700">
            點我觀看所有產品
        </button>
    </a>
</div>
@endsection