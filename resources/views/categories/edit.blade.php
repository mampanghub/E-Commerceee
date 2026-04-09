<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('categories.index') }}"
                class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Manajemen Data</p>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Kategori</h1>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">

                <form action="{{ route('categories.update', $category->category_id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <input type="text" name="nama_kategori"
                                value="{{ $category->nama_kategori }}"
                                class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all"
                                placeholder="Nama kategori"
                                required autofocus>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 font-medium">ID: #{{ $category->category_id }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                        <a href="{{ route('categories.index') }}"
                            class="px-5 py-2.5 text-sm font-black text-slate-500 hover:text-slate-700 transition-all">
                            ← Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white font-black text-sm rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                            Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
