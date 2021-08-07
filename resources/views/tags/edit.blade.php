<x-app-layout>
    <!-- breeze header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('標籤編號') }} - {{ $tag->id }} - 編輯
        </h2>
    </x-slot>
    <!-- breeze layout -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- content -->
                    <div class="mx-auto">
                        <form class="py-4" action="{{ route('tags.update', $tag->id) }}" method="POST">
                            @csrf
                            @method("PATCH")
                            <div class="bg-red-200 shadow-lg rounded-3xl px-8 py-8 flex flex-col mx-auto">
                                <!-- 名稱 -->
                                <div class="mb-4">
                                    <label class="labelText" for="title">
                                        標籤名稱
                                    </label>
                                    <input class="input-shadow text-grey-darker input-focus-blue" name="title" type="text" value="{{ $tag->title }}" required>
                                </div>
                                <!-- 操作按鈕 -->
                                <div class="text-center mt-8">
                                    <a href="{{ route('tags.index') }}" class="border-2 rounded-full font-bold text-white p-4 px-8 m-6 transition-300-ease-in-out bg-red-500 hover:bg-red-700">
                                        取消
                                    </a>
                                    <button type="submit" class="border-2 rounded-full font-bold text-white p-4 px-8 m-6 transition-300-ease-in-out bg-green-500 hover:bg-green-700">
                                        變更
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>