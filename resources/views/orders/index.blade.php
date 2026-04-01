<x-app-layout>
    <div class="min-h-screen bg-blue-50/40">

        {{-- HEADER --}}
        <div class="bg-white border-b border-blue-100">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500">Admin</p>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pesanan Masuk</h1>
                    </div>
                    <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-black uppercase tracking-widest border border-blue-100">
                        {{ $orders->count() }} Order
                    </span>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            @if($orders->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-100 py-24 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <p class="font-black text-slate-400 text-sm">Belum ada pesanan masuk</p>
            </div>
            @else

            {{-- Filter tabs --}}
            @php
                $allStatuses = ['semua', 'dibayar', 'dikemas', 'dikirim', 'selesai', 'batal'];
                $activeTab = request('status', 'semua');
            @endphp
            <div class="bg-blue-100/60 rounded-2xl p-1.5 flex overflow-x-auto mb-6 gap-0.5">
                @foreach($allStatuses as $s)
                @php
                    $count = $s === 'semua' ? $orders->count() : $orders->where('status', $s)->count();
                    $isActive = $activeTab === $s;
                @endphp
                <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
                    class="shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap
                    {{ $isActive
                        ? 'bg-white text-blue-600 shadow-sm shadow-blue-100'
                        : 'text-blue-400 hover:text-blue-600 hover:bg-white/50' }}">
                    {{ ucfirst($s) }}
                    @if($count > 0)
                        <span class="inline-flex items-center justify-center w-4 h-4 rounded-full text-[9px] font-black
                            {{ $isActive ? 'bg-blue-600 text-white' : 'bg-blue-200/70 text-blue-500' }}">
                            {{ $count }}
                        </span>
                    @endif
                </a>
                @endforeach
            </div>

            {{-- Order list --}}
            <div class="space-y-3">
                @php
                    $filtered = $activeTab === 'semua' ? $orders : $orders->where('status', $activeTab);
                @endphp

                @forelse($filtered as $order)
                @php
                    $firstItem = $order->items->first();
                    $displayImage = null;
                    if ($firstItem && $firstItem->product) {
                        $displayImage = $firstItem->product->images->where('variant_id', $firstItem->variant_id)->first()
                            ?? $firstItem->product->primaryImage
                            ?? $firstItem->product->images->first();
                    }

                    $statusConfig = [
                        'dibayar' => ['bg-blue-50 text-blue-700',      'Dibayar'],
                        'dikemas' => ['bg-indigo-50 text-indigo-700',   'Dikemas'],
                        'dikirim' => ['bg-violet-50 text-violet-700',   'Dikirim'],
                        'selesai' => ['bg-emerald-50 text-emerald-700', 'Selesai'],
                        'batal'   => ['bg-rose-50 text-rose-700',       'Dibatalkan'],
                    ];
                    [$badgeClass, $statusLabel] = $statusConfig[$order->status] ?? ['bg-slate-50 text-slate-600', ucfirst($order->status)];
                @endphp

                <div class="bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-4 p-4">

                        {{-- Foto --}}
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                            @if($displayImage)
                                <img src="{{ asset('storage/' . $displayImage->gambar) }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://placehold.co/56x56?text=?'">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info utama --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-black text-slate-900 text-sm">Order #{{ $order->order_id }}</p>
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-400 mb-1">
                                <span class="font-bold text-slate-600">{{ $order->user->name }}</span>
                                · {{ $order->created_at->diffForHumans() }}
                            </p>

                            {{-- Produk --}}
                            <div class="flex flex-wrap gap-1">
                                @foreach($order->items as $it)
                                <span class="text-[10px] bg-blue-50 text-blue-500 px-2 py-0.5 rounded-lg border border-blue-100 font-medium">
                                    {{ $it->jumlah }}× {{ Str::limit($it->product->nama_produk ?? 'Produk Dihapus', 20) }}
                                </span>
                                @endforeach
                            </div>

                            {{-- Kurir & resi --}}
                            @if(in_array($order->status, ['dikirim', 'selesai']) && $order->nama_kurir)
                            <p class="text-[10px] font-black text-violet-500 mt-1.5 uppercase tracking-wide">
                                {{ $order->nama_kurir }} · {{ $order->nomor_resi }}
                            </p>
                            @endif
                        </div>

                        {{-- Total + aksi --}}
                        <div class="flex items-center gap-4 shrink-0">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total</p>
                                <p class="font-black text-slate-900 text-sm">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('orders.show', $order->order_id) }}"
                                class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-black hover:bg-blue-700 transition-all">
                                Kelola →
                            </a>
                        </div>

                    </div>
                </div>

                @empty
                <div class="bg-white rounded-2xl border border-slate-100 py-16 text-center">
                    <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Tidak ada order {{ $activeTab }}</p>
                </div>
                @endforelse
            </div>

            @endif
        </div>
    </div>
</x-app-layout>