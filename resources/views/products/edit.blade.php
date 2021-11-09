<x-app-layout>
    <!-- breeze header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品編號') }} - {{ $product->id }} - 編輯
        </h2>
    </x-slot>
    <!-- breeze layout -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- content -->
                    <div class="mx-auto">
                        <form class="py-4" action="{{ route('products.update', $product->id) }}" method="POST">
                            @csrf
                            @method("PATCH")
                            <div class="bg-red-200 shadow-lg rounded-3xl px-8 py-8 flex flex-col mx-auto">
                                <!-- 名稱 -->
                                <div class="mb-4">
                                    <label class="labelText" for="title">
                                        商品名稱
                                    </label>
                                    <br />
                                    <input class="input-shadow text-grey-darker input-focus-blue" name="title" type="text" value="{{ $product->title }}" required>
                                </div>
                                <!-- 敘述 -->
                                <div class="mb-4">
                                    <label class="labelText" for="description">
                                        商品敘述
                                    </label>
                                    <br />
                                    <textarea rows="5" class="input-shadow text-grey-darker mb-3 input-focus-blue" name="description" type="text" required>
                                        {{ $product->description }}
                                    </textarea>
                                </div>
                                <!-- 圖片 -->
                                <div class="mb-4">
                                    <label class="labelText" for="imgUrl">
                                        商品圖片網址
                                    </label>
                                    <br />
                                    <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="imgUrl" type="text" value="{{ $product->imgUrl }}" required>
                                </div>
                                <!-- 單價 -->
                                <div class="mb-4">
                                    <label class="labelText" for="unit_price">
                                        商品單價
                                    </label>
                                    <br />
                                    <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="unit_price" type="number" value="{{ $product->unit_price }}" required>
                                </div>
                                <!-- 數量 -->
                                <div class="mb-4">
                                    <label class="labelText" for="stock_quantity">
                                        存貨數量
                                    </label>
                                    <br />
                                    <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="stock_quantity" type="number" value="{{ $product->stock_quantity }}" required>
                                </div>
                                @if (count($product->variations) !== 0)
                                <!-- 規格 -->
                                <div class="mb-4">
                                    <label class="labelText" for="variations">
                                        商品規格
                                    </label>
                                    <br />
                                    <div class="product_variations_container">
                                        @foreach ($product->variations as $key => $variation)
                                        <div class="product_variations mb-16 flex flex-col md:flex-row">
                                            <!-- title -->
                                            <div class="variation_title">
                                                <input class="input-shadow text-grey-darker mb-2 input-focus-blue" name="variation_title[]" type="text" value="{{ $variation->title }}" placeholder="ex: 尺寸">
                                                <!-- delete variation button -->
                                                <!-- 尚未完成(測試中) -->
                                                <!-- <form action="{{ route('products.deleteVariation', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method("DELETE")
                                                    <button class="border-2 rounded-xl font-bold text-white tracking-widest p-2 mx-2 transition-300-ease-in-out bg-red-500 hover:bg-red-700"">刪除</button>
                                                </form> -->
                                            </div>
                                            <!-- options -->
                                            <div class="variation_options flex flex-col ml-8 md:w-1/2">
                                                @foreach ($variation->options as $option)
                                                <input class="input-shadow text-grey-darker mb-2 input-focus-blue mb-4" name="variation_options_{{ $key }}[]" type="text" value="{{ $option }}" placeholder="ex: XL">
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <label class="labelText text-pink-600 mb-4" for="variations">
                                    此商品目前並未設定規格
                                </label>
                                @endif
                                <!-- 折價率 -->
                                <div class="mb-4">
                                    <label class="labelText" for="discount_rate input-focus-blue">
                                        商品折價率(預設/最多 1)
                                    </label>
                                    <br />
                                    <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="discount_rate" type="number" step="0.01" value="{{ $product->discount_rate }}" max="1">
                                </div>
                                <!-- 星級評價 -->
                                <div class="mb-4">
                                    <label class="labelText" for="discount_rate input-focus-blue">
                                        星級評價(預設/最多 5 )
                                    </label>
                                    <br />
                                    <input class="input-shadow text-grey-darker mb-3 input-focus-blue" name="rating" type="number" step="0.01" placeholder="5.00" value="{{ $product->rating }}" max="5">
                                </div>
                                <!-- 是否有現貨 -->
                                <div class="mb-4">
                                    <label class="labelText" for="available">
                                        是否有貨
                                    </label>
                                    <br />
                                    <select name="available">
                                        <option value="1" @if($product->available == 1) selected @endif>是</option>
                                        <option value="0" @if($product->available == 0) selected @endif>否</option>
                                    </select>
                                </div>
                                <!-- 標籤選擇 -->
                                <fieldset class="mb-4">
                                    <label class="labelText">產品標籤</label>
                                    <div class="flex flex-wrap">
                                        @foreach($tags as $tag)
                                        <label class="mr-2 mt-2 p-3 py-1 bg-white rounded-2xl font-semibold">
                                            <!-- 預設打勾的選項 (若 tag->id 有包含在內 ) -->
                                            <input name="tags[]" type="checkbox" value="{{ $tag->id }}" @if($product->tags->contains($tag->id)) checked @endif>
                                            {{ $tag->title }}
                                        </label>
                                        @endforeach
                                    </div>
                                </fieldset>
                                <!-- 操作按鈕 -->
                                <div class="text-center mt-8">
                                    <a href="{{ route('products.index') }}" class="border-2 rounded-full font-bold text-white p-4 px-6 mx-2 md:m-6 transition-300-ease-in-out bg-red-500 hover:bg-red-700">
                                        取消
                                    </a>
                                    <button type="submit" class="border-2 rounded-full font-bold text-white p-4 px-6 mx-2 md:m-6 transition-300-ease-in-out bg-green-500 hover:bg-green-700">
                                        變更
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- 尚未完成(測試中) -->
                        <!-- <form action="{{ route('products.deleteVariation', $product->id) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="border-2 rounded-xl font-bold text-white tracking-widest p-2 mx-2 transition-300-ease-in-out bg-red-500 hover:bg-red-700"">刪除</button>
                        </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>