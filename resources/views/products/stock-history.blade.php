<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('products.index') }}"
                    class="p-2 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Riwayat Stok</p>
                    <h2 class="font-black text-2xl text-gray-800 leading-tight">{{ $product->nama_produk }}</h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- STAT PER VARIAN --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->variants as $variant)
                <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ $variant->nama_varian }}</p>
                    <p class="text-2xl font-black {{ $variant->stok <= 5 ? 'text-red-600' : 'text-indigo-600' }}">
                        {{ $variant->stok }}
                        <span class="text-sm font-bold text-gray-400">pcs</span>
                    </p>
                    @if($variant->stok <= 5)
                        <p class="text-[10px] font-bold text-red-400 mt-1">⚠ Stok menipis</p>
                    @else
                        <p class="text-[10px] font-bold text-emerald-500 mt-1">✓ Stok aman</p>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- TABEL RIWAYAT --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <p class="text-sm font-black text-gray-700">
                        Semua Pergerakan Stok
                        <span class="ml-2 px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold">
                            {{ $logs->total() }} entri
                        </span>
                    </p>
                    <p class="text-xs text-gray-400 italic">Terbaru di atas</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Varian</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Tipe</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Jumlah</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Stok Lama</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Stok Baru</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($logs as $log)
                            <tr class="hover:bg-indigo-50/30 transition duration-200">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-700">{{ $log->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        {{ $log->variant->nama_varian ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($log->tipe === 'masuk')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                            </svg>
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-50 text-rose-600 border border-rose-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-black {{ $log->tipe === 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $log->tipe === 'masuk' ? '+' : '-' }}{{ $log->jumlah }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-gray-400 line-through">{{ $log->stok_lama }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-black text-gray-800">{{ $log->stok_baru }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-500">{{ $log->keterangan ?? '-' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Belum ada riwayat stok</h3>
                                        <p class="text-gray-400 text-sm mt-1">Pergerakan stok akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>