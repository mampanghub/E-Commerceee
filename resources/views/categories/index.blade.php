<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Admin Panel</p>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kategori Produk</h1>
            </div>
            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($categories->count() > 0)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <div
                            style="background: var(--color-background-primary, #fff); border-radius: 20px; border: 0.5px solid var(--color-border-tertiary);">

                            {{-- Header info bar --}}
                            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Semua Kategori
                                </h3>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-black text-slate-400">
                                        Total: <span class="text-blue-600">{{ $categories->count() }}</span> kategori
                                    </span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    <span class="text-xs font-black text-slate-400">
                                        <span class="text-blue-600">
                                            {{ $categories->sum(fn($c) => $c->products_count ?? $c->products->count()) }}
                                        </span> produk
                                    </span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-50 border-b border-slate-100">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16">
                                                No</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                Nama Kategori</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                Jumlah Produk</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach ($categories as $item)
                                            @php
                                                $colors = [
                                                    [
                                                        'bg' => 'bg-blue-50',
                                                        'text' => 'text-blue-600',
                                                        'icon' => 'text-blue-500',
                                                        'iconbg' => 'bg-blue-50',
                                                    ],
                                                    [
                                                        'bg' => 'bg-violet-50',
                                                        'text' => 'text-violet-600',
                                                        'icon' => 'text-violet-500',
                                                        'iconbg' => 'bg-violet-50',
                                                    ],
                                                    [
                                                        'bg' => 'bg-amber-50',
                                                        'text' => 'text-amber-600',
                                                        'icon' => 'text-amber-500',
                                                        'iconbg' => 'bg-amber-50',
                                                    ],
                                                    [
                                                        'bg' => 'bg-emerald-50',
                                                        'text' => 'text-emerald-600',
                                                        'icon' => 'text-emerald-500',
                                                        'iconbg' => 'bg-emerald-50',
                                                    ],
                                                    [
                                                        'bg' => 'bg-pink-50',
                                                        'text' => 'text-pink-600',
                                                        'icon' => 'text-pink-500',
                                                        'iconbg' => 'bg-pink-50',
                                                    ],
                                                ];
                                                $c = $colors[$loop->index % count($colors)];
                                                $jumlahProduk = $item->products_count ?? $item->products->count();
                                            @endphp
                                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="flex items-center justify-center w-8 h-8 rounded-xl bg-slate-100 text-xs font-black text-slate-500 group-hover:bg-white group-hover:border group-hover:border-slate-200 transition-all">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-9 h-9 {{ $c['iconbg'] }} {{ $c['icon'] }} rounded-2xl flex items-center justify-center shrink-0">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                                            {{ $item->nama_kategori }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="text-xs font-black {{ $c['text'] }} {{ $c['bg'] }} px-3 py-1.5 rounded-xl">
                                                        {{ $jumlahProduk }} produk
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="{{ route('categories.edit', $item->category_id) }}"
                                                            class="p-2 bg-amber-50 text-amber-500 rounded-xl hover:bg-amber-100 transition-colors border border-amber-100"
                                                            title="Edit">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <form
                                                            action="{{ route('categories.destroy', $item->category_id) }}"
                                                            method="POST" class="inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Hapus kategori ini?')"
                                                                class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-colors border border-red-100"
                                                                title="Hapus">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm py-20 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <p class="font-black text-slate-700">Belum ada kategori</p>
                    <p class="text-sm text-slate-400 mt-1 mb-6">Buat kategori pertama untuk mulai mengelola produk.</p>
                    <a href="{{ route('categories.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-black text-sm rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                        Buat Kategori Pertama
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
