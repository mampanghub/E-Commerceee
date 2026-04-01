<x-app-layout>
    {{-- HERO SECTION - KATEGORI --}}
    <div class="relative overflow-hidden bg-white border-b border-slate-100">
        {{-- Background Accent --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[80%] bg-blue-400 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span
                        class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-xs font-black uppercase tracking-widest rounded-full mb-4">
                        Manajemen Data
                    </span>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-2 tracking-tighter">
                        Kategori <span class="text-blue-600">Produk</span>
                    </h1>
                    <p class="text-base text-slate-500 font-medium leading-relaxed">
                        Kelola kategori untuk mempermudah pembeli menemukan produk Anda.
                    </p>
                </div>

                {{-- TOMBOL TAMBAH - SEKARANG BIRU STANDAR --}}
                <a href="{{ route('categories.create') }}"
                    class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all duration-300 shadow-xl shadow-blue-100 transform hover:-translate-y-1 active:scale-95 group">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Kategori</span>
                </a>
            </div>
        </div>
    </div>

    <div class="py-12 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($categories->count() > 0)
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                                    <th class="px-8 pb-2">No</th>
                                    <th class="px-6 pb-2">Nama Kategori</th>
                                    <th class="px-8 pb-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $item)
                                    <tr class="group transition-all duration-300">
                                        {{-- No --}}
                                        <td
                                            class="px-8 py-6 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 rounded-l-[2rem] transition-all border-y border-l border-transparent group-hover:border-slate-100">
                                            <span
                                                class="flex items-center justify-center w-8 h-8 rounded-xl bg-white border border-slate-200 text-xs font-black text-slate-400 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>

                                        {{-- Nama Kategori --}}
                                        <td
                                            class="px-6 py-6 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 transition-all border-y border-transparent group-hover:border-slate-100">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <span
                                                    class="text-base font-black text-slate-800 uppercase tracking-tight">
                                                    {{ $item->nama_kategori }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Aksi --}}
                                        <td
                                            class="px-8 py-6 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 rounded-r-[2rem] transition-all border-y border-r border-transparent group-hover:border-slate-100 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                {{-- TOMBOL EDIT --}}
                                                <a href="{{ route('categories.edit', $item->category_id) }}"
                                                    class="p-3 bg-white border border-slate-100 text-amber-500 hover:bg-amber-50 hover:border-amber-100 rounded-2xl transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 active:scale-95"
                                                    title="Edit Kategori">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                {{-- TOMBOL HAPUS --}}
                                                <form action="{{ route('categories.destroy', $item->category_id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-3 bg-white border border-slate-100 text-red-500 hover:bg-red-50 hover:border-red-100 rounded-2xl transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 active:scale-95"
                                                        onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                                        title="Hapus Kategori">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 px-4 flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                            Total: <span class="text-blue-600">{{ $categories->count() }}</span> Kategori
                        </p>
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200 shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tighter">Belum Ada Kategori</h3>
                    <p class="text-slate-500 font-medium mt-2 mb-8">Data kategori masih kosong, silakan tambah kategori
                        baru.</p>
                    <a href="{{ route('categories.create') }}"
                        class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                        Buat Kategori Pertama
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
