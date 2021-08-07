<x-app-layout>
    <!-- breeze header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('所有商品') }}
        </h2>
    </x-slot>
    <!-- breeze layout -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- content -->
                    <div class="shadow-lg bg-gray-100 mb-12 rounded-3xl overflow-auto mx-auto">
                        <table class="table-auto bg-white border-collapse">
                            <thead class="bg-pink-500 text-white">
                                <tr>
                                    <th class="w-1/12 p-4">編號</th>
                                    <th class="w-2/12 p-4">名稱</th>
                                    <th class="w-1/12 p-4">圖片</th>
                                    <th class="w-4/12 p-4">敘述</th>
                                    <th class="w-2/12 p-4">單價</th>
                                    <th class="w-3/12 p-4">操作</th>
                                </tr>
                            </thead>
                            <tbody class="border border-gray-300">
                                @foreach($products as $product)
                                <tr class="mt-2 text-center  even:bg-gray-100">
                                    <!-- 產品編號 -->
                                    <td class="w-1/12 p-4">{{ $product->id }}</td>
                                    <!-- 產品名稱 -->
                                    <td class="w-2/12 p-4">{{ $product->title }}</td>
                                    <!-- 產品圖片 -->
                                    <td class="w-1/12 p-4">
                                        <img src="{{ $product->imgUrl }}" alt="{{ $product->title }}" width="100" height="100">
                                    </td>
                                    <!-- 產品敘述 -->
                                    <td class="w-4/12 p-4">{{ Str::limit($product->description, 80, $end='...') }}</td>
                                    <!-- 產品單價 -->
                                    <td class="w-2/12 p-4">{{ $product->unit_price }}</td>
                                    <!-- 操作按鈕 -->
                                    <td>
                                        <a href="{{ route('products.show', $product->id) }}">
                                            <button class="rounded-full bg-green-500 hover:bg-green-600 px-4 py-2 text-white outline-none m-2">
                                                查看
                                            </button>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}">
                                            <button class="rounded-full bg-red-400 hover:bg-red-600 px-4 py-2 text-white outline-none m-2">
                                                編輯
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if (Session::has('message'))
                            <div class="flash_message fixed bottom-0 left-0 transform translate-x-1/2 bg-green-200 px-6 py-4 mx-2 my-4 rounded-md text-lg flex items-center mx-auto w-3/4 xl:w-2/4">
                                <svg viewBox="0 0 24 24" class="text-green-600 w-5 h-5 sm:w-5 sm:h-5 mr-3">
                                    <path fill="currentColor" d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z"></path>
                                </svg>
                                <span class="text-green-800">{{ Session::get('message') }}</span>
                            </div>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>