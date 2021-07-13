<x-app-layout>
    <!-- breeze header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('主控面板') }}
        </h2>
    </x-slot>
    <!-- breeze layout -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- content -->
                    <div class="font-semibold text-xl text-gray-800 leading-tight">
                        你已經登入，目前身分為: {{ Auth::user()->is_admin ? '管理者' : '訪客' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
