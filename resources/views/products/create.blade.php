@extends('layouts.app')


<body>
    <p class="italic text-center">產品上架後台</p>
    <form class="product_form" action="/api/products" method="POST">
        @csrf
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">

            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="title">
                    商品名稱
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="title" type="text" placeholder="優質手機">
            </div>
            
            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="description">
                    商品敘述
                </label>
                <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" id="description" type="text" placeholder="Lorem....">
            </div>

            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="imgUrl">
                    商品圖片網址
                </label>
                <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" id="imgUrl" type="text" placeholder="http://.....">
            </div>


        </div>
    </form>
</body>