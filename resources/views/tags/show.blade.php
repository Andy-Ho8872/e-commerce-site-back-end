<x-app-layout>
    <!-- breeze header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('標籤編號') }} - {{ $tag->id }} - 查看
        </h2>
    </x-slot>
    <!-- breeze layout -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- content -->
                    <div class="mx-auto">
                        <form class="py-4" method="POST">
                            <div class="bg-blue-200 shadow-lg rounded-3xl px-8 py-8 flex flex-col mx-auto">
                                <!-- 名稱 -->
                                <div class="mb-4">
                                    <label class="labelText" for="title">
                                        標籤名稱
                                    </label>
                                    <input class="input-shadow text-grey-darker input-focus-blue" name="title" type="text" value="{{ $tag->title }}" readonly>
                                </div>
                                <!-- 提示訊息 -->
                                @if (Session::has('message'))
                                <div class="flash_message block bg-green-200 px-6 py-4 mx-2 my-4 rounded-md text-lg flex items-center mx-auto w-3/4 xl:w-2/4">
                                    <svg viewBox="0 0 24 24" class="text-green-600 w-5 h-5 sm:w-5 sm:h-5 mr-3">
                                        <path fill="currentColor" d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z"></path>
                                    </svg>
                                    <span class="text-green-800">{{ Session::get('message') }}</span>
                                </div>
                                @endif
                                <!-- 操作按鈕 -->
                                <div class="text-center mt-8">
                                    <a href="{{ route('tags.index') }}" class="border-2 rounded-full font-bold text-white p-4 px-6 mx-2 md:m-6 transition-300-ease-in-out bg-green-500 hover:bg-green-700">
                                        返回
                                    </a>
                                    <a href="{{ route('tags.edit', $tag->id) }}" class="border-2 rounded-full font-bold text-white p-4 px-6 mx-2 md:m-6 transition-300-ease-in-out bg-red-500 hover:bg-red-700">
                                        編輯
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>