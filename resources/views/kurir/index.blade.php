<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        {{-- HEADER --}}
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-black uppercase tracking-widest text-blue-500 bg-blue-50 px-3 py-1 rounded-full">Kurir Dashboard</span>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight mt-2">
                            Halo, {{ Auth::user()->name }} 👋
                        </h1>
                        <p class="text-sm text-slate-400 font-medium mt-1">Ambil paket yang tersedia, siapa cepat dia dapat!</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="bg-violet-50 border border-violet-100 rounded-2xl px-5 py-3 text-center">
                            <div class="text-2xl font-black text-violet-600">{{ $tersedia->count() }}</div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-violet-400">Tersedia</div>
                        </div>
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-3 text-center">
                            <div class="text-2xl font-black text-blue-600">{{ $aktif->where('status', 'dikemas')->count() }}</div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-blue-400">Perlu Dikemas</div>
                        </div>
                        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 text-center">
                            <div class="text-2xl font-black text-amber-600">{{ $aktif->where('status', 'dikirim')->count() }}</div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-amber-400">Sedang Dikirim</div>
                        </div>
                        <a href="{{ route('kurir.saldo') }}"
                            class="bg-indigo-50 border border-indigo-100 rounded-2xl px-5 py-3 text-center hover:bg-indigo-100 transition-all">
                            <div class="text-2xl font-black text-indigo-600">Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}</div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Saldo Saya</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl font-semibold text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl font-semibold text-sm">
                    ✕ {{ session('error') }}
                </div>
            @endif

            {{-- TABS --}}
            <div class="bg-slate-100/80 rounded-2xl p-1.5 flex gap-1 w-fit">
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'tersedia']) }}"
                    class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all
                    {{ $tab === 'tersedia' ? 'bg-white text-violet-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                    Tersedia
                    @if($tersedia->count() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-[9px]
                            {{ $tab === 'tersedia' ? 'bg-violet-600 text-white' : 'bg-slate-300 text-slate-500' }}">
                            {{ $tersedia->count() }}
                        </span>
                    @endif
                </a>
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'aktif']) }}"
                    class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all
                    {{ $tab === 'aktif' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                    Aktif
                    @if($aktif->count() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-[9px]
                            {{ $tab === 'aktif' ? 'bg-blue-600 text-white' : 'bg-slate-300 text-slate-500' }}">
                            {{ $aktif->count() }}
                        </span>
                    @endif
                </a>
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'history']) }}"
                    class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all
                    {{ $tab === 'history' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                    Riwayat
                    @if($history->total() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-[9px]
                            {{ $tab === 'history' ? 'bg-blue-600 text-white' : 'bg-slate-300 text-slate-500' }}">
                            {{ $history->total() }}
                        </span>
                    @endif
                </a>
            </div>

            {{-- TAB TERSEDIA --}}
            @if($tab === 'tersedia')
                @if($tersedia->isEmpty())
                    <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center">
                        <div class="w-16 h-16 bg-violet-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="font-black text-slate-700">Belum ada paket tersedia</p>
                        <p class="text-slate-400 text-sm mt-1">Paket yang sudah dibayar pembeli akan muncul di sini.</p>
                    </div>
                @else
                    <p class="text-xs text-slate-400 font-bold -mb-1">
                        🏃 Siapa cepat dia dapat! {{ $tersedia->count() }} paket menunggu diambil.
                    </p>
                    @foreach($tersedia as $order)
                    <div class="bg-white rounded-3xl border border-violet-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="px-6 py-3 bg-violet-600 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                <span class="text-white text-xs font-black uppercase tracking-widest">Menunggu Kurir</span>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($order->shippingOption)
                                    <span class="text-white/80 text-xs font-bold bg-white/10 px-2 py-0.5 rounded-lg">{{ $order->shippingOption->label }}</span>
                                @endif
                                <span class="text-white/80 text-xs font-bold">#{{ $order->order_id }}</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row gap-6">
                                {{-- Produk --}}
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Isi Paket</p>
                                    <div class="space-y-2">
                                        @foreach($order->items->take(2) as $item)
                                        <div class="flex items-center gap-3">
                                            @if($item->product->primaryImage)
                                                <img src="{{ Storage::url($item->product->primaryImage->gambar) }}"
                                                    class="w-10 h-10 rounded-xl object-cover bg-slate-100">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-slate-100"></div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $item->product->nama_produk }}</p>
                                                @if($item->variant)
                                                    <p class="text-xs text-slate-400">{{ $item->variant->nama_varian }} · {{ $item->jumlah }}x</p>
                                                @else
                                                    <p class="text-xs text-slate-400">{{ $item->jumlah }}x</p>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <p class="text-xs text-slate-400 font-semibold">+{{ $order->items->count() - 2 }} item lainnya</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Alamat Tujuan</p>
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-violet-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-black text-slate-800">{{ $order->nama_penerima ?? $order->user->name }}</p>
                                            <p class="text-xs text-blue-600 font-bold mt-0.5">{{ $order->no_telp_penerima ?? $order->user->no_telp }}</p>
                                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $order->shipping_address ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Aksi --}}
                                <div class="lg:w-48 flex flex-col gap-2">
                                    <div class="bg-violet-50 rounded-2xl p-3 text-center">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-violet-400 mb-0.5">Ongkir kamu</p>
                                        <p class="text-lg font-black text-violet-700">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
                                    </div>
                                    <a href="{{ route('kurir.orders.show', $order->order_id) }}"
                                        class="text-center px-4 py-2.5 bg-slate-100 text-slate-700 text-sm font-black rounded-2xl hover:bg-slate-200 transition-all">
                                        Lihat Detail
                                    </a>
                                    <form action="{{ route('kurir.orders.take', $order->order_id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Ambil paket #{{ $order->order_id }}? Kamu bertanggung jawab untuk mengemas dan mengirimnya.')"
                                            class="w-full px-4 py-2.5 bg-violet-600 text-white text-sm font-black rounded-2xl hover:bg-violet-700 transition-all shadow-md shadow-violet-100">
                                            🚀 Ambil Paket
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endif

            {{-- TAB AKTIF --}}
            @if($tab === 'aktif')
                @if($aktif->isEmpty())
                    <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="font-black text-slate-700">Tidak ada paket aktif</p>
                        <p class="text-slate-400 text-sm mt-1">Ambil paket dari tab <span class="text-violet-600 font-black">Tersedia</span> dulu ya!</p>
                    </div>
                @endif

                @foreach($aktif as $order)
                @php
                    $isPending = $order->status === 'dikemas';
                    $isOngoing = $order->status === 'dikirim';
                @endphp
                <div class="bg-white rounded-3xl border {{ $isPending ? 'border-blue-100' : 'border-amber-100' }} overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="px-6 py-3 {{ $isPending ? 'bg-blue-600' : 'bg-amber-500' }} flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            <span class="text-white text-xs font-black uppercase tracking-widest">
                                {{ $isPending ? 'Proses Pengemasan' : 'Sedang Dikirim' }}
                            </span>
                        </div>
                        <span class="text-white/80 text-xs font-bold">#{{ $order->order_id }}</span>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            {{-- Produk --}}
                            <div class="flex-1">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Isi Paket</p>
                                <div class="space-y-2">
                                    @foreach($order->items->take(2) as $item)
                                    <div class="flex items-center gap-3">
                                        @if($item->product->primaryImage)
                                            <img src="{{ Storage::url($item->product->primaryImage->gambar) }}"
                                                class="w-10 h-10 rounded-xl object-cover bg-slate-100">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-slate-100"></div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ $item->product->nama_produk }}</p>
                                            @if($item->variant)
                                                <p class="text-xs text-slate-400">{{ $item->variant->nama_varian }} · {{ $item->jumlah }}x</p>
                                            @else
                                                <p class="text-xs text-slate-400">{{ $item->jumlah }}x</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($order->items->count() > 2)
                                        <p class="text-xs text-slate-400 font-semibold">+{{ $order->items->count() - 2 }} item lainnya</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="flex-1">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Alamat Tujuan</p>
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-black text-slate-800">{{ $order->nama_penerima ?? $order->user->name }}</p>
                                        <p class="text-xs text-blue-600 font-bold mt-0.5">{{ $order->no_telp_penerima ?? $order->user->no_telp }}</p>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $order->shipping_address ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Aksi --}}
                            <div class="lg:w-48 flex flex-col gap-2">
                                @if($isOngoing && $order->nomor_resi)
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Nomor Resi</p>
                                    <p class="text-xs font-black text-slate-800 break-all font-mono">{{ $order->nomor_resi }}</p>
                                </div>
                                @endif
                                <a href="{{ route('kurir.orders.show', $order->order_id) }}"
                                    class="text-center px-4 py-2.5 bg-slate-100 text-slate-700 text-sm font-black rounded-2xl hover:bg-slate-200 transition-all">
                                    Lihat Detail
                                </a>
                                @if($isPending)
                                <form action="{{ route('kurir.orders.update-status', $order->order_id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="dikirim">
                                    <button type="submit" onclick="return confirm('Paket sudah dikemas dan siap dikirim ke #{{ $order->order_id }}?')"
                                        class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-black rounded-2xl hover:bg-blue-700 transition-all">
                                        📦 Mulai Kirim
                                    </button>
                                </form>
                                @elseif($isOngoing)
                                <a href="{{ route('kurir.orders.show', $order->order_id) }}"
                                    class="text-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-black rounded-2xl hover:bg-emerald-700 transition-all">
                                    ✓ Konfirmasi Diterima
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            {{-- TAB HISTORY --}}
            @if($tab === 'history')
                @if($history->isEmpty())
                    <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center">
                        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                            </svg>
                        </div>
                        <p class="font-black text-slate-700">Belum ada riwayat</p>
                        <p class="text-slate-400 text-sm mt-1">Pengiriman yang selesai akan muncul di sini.</p>
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                        <div class="divide-y divide-slate-50">
                            @foreach($history as $order)
                            <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition-colors">
                                @php $firstItem = $order->items->first(); @endphp
                                <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 shrink-0">
                                    @if($firstItem?->product?->primaryImage)
                                        <img src="{{ Storage::url($firstItem->product->primaryImage->gambar) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-black text-slate-800 text-sm">Order #{{ $order->order_id }}</p>
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg">Selesai</span>
                                        @if($order->shippingOption)
                                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-500 text-[10px] font-black rounded-lg">{{ $order->shippingOption->label }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        <span class="font-bold">{{ $order->nama_penerima ?? $order->user->name }}</span>
                                        · {{ $order->shipping_address ?? '-' }}
                                    </p>
                                    <p class="text-[10px] text-slate-300 mt-0.5">
                                        {{ \Carbon\Carbon::parse($order->updated_at)->translatedFormat('d M Y, H:i') }} WIB
                                    </p>
                                </div>
                                <div class="text-right shrink-0 flex flex-col items-end gap-2">
                                    <p class="font-black text-emerald-600 text-sm">+Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
                                    <a href="{{ route('kurir.orders.show', $order->order_id) }}"
                                        class="text-xs font-black text-blue-500 hover:text-blue-700 hover:underline">
                                        Lihat →
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($history->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100">
                            {{ $history->appends(['tab' => 'history'])->links() }}
                        </div>
                        @endif
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
