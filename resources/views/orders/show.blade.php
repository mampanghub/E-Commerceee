<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        {{-- HEADER --}}
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center gap-4">
                    @php $backUrl = Auth::user()->role == 'admin' ? route('orders.index') : route('orders.history'); @endphp
                    <a href="{{ $backUrl }}" class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500">Detail Pesanan</p>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Order #{{ $order->order_id }}</h1>
                    </div>
                    <div class="ml-auto">
                        @php
                            $statusConfig = [
                                'dibayar' => ['bg-blue-50 text-blue-700 border-blue-200', 'Dibayar'],
                                'dikemas' => ['bg-indigo-50 text-indigo-700 border-indigo-200', 'Sedang Dikemas'],
                                'dikirim' => ['bg-violet-50 text-violet-700 border-violet-200', 'Sedang Dikirim'],
                                'selesai' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'Selesai'],
                                'batal' => ['bg-rose-50 text-rose-700 border-rose-200', 'Dibatalkan'],
                            ];
                            [$badgeClass, $statusLabel] = $statusConfig[$order->status] ?? [
                                'bg-slate-50 text-slate-700 border-slate-200',
                                $order->status,
                            ];

                            // Helper untuk format range tanggal
                            $formatRange = function ($min, $max) {
                                if (!$min) {
                                    return null;
                                }
                                $tglMin = \Carbon\Carbon::parse($min);
                                $tglMax = $max ? \Carbon\Carbon::parse($max) : null;
                                if (!$tglMax || $tglMin->eq($tglMax)) {
                                    return $tglMin->translatedFormat('d M Y');
                                }
                                if ($tglMin->month === $tglMax->month) {
                                    return $tglMin->format('d') . ' - ' . $tglMax->translatedFormat('d M Y');
                                }
                                return $tglMin->translatedFormat('d M') . ' - ' . $tglMax->translatedFormat('d M Y');
                            };
                        @endphp
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black border uppercase tracking-widest {{ $badgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- KOLOM KIRI --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- TIMELINE --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Status
                            Pengiriman</p>
                        @php
                            $steps = [
                                [
                                    'label' => 'Pembayaran Dikonfirmasi',
                                    'sub' => $order->created_at->format('d M Y • H:i') . ' WIB',
                                    'done' => true,
                                ],
                                [
                                    'label' => 'Sedang Dikemas',
                                    'sub' => 'Pesanan sedang diproses',
                                    'done' => in_array($order->status, ['dikemas', 'dikirim', 'selesai']),
                                ],
                                [
                                    'label' => 'Paket Dikirim',
                                    'sub' => $order->nomor_resi ? 'Resi: ' . $order->nomor_resi : 'Belum dikirim',
                                    'done' => in_array($order->status, ['dikirim', 'selesai']),
                                ],
                                [
                                    'label' => 'Pesanan Diterima',
                                    'sub' => 'Transaksi selesai',
                                    'done' => $order->status === 'selesai',
                                ],
                            ];
                        @endphp

                        @foreach ($steps as $i => $step)
                            <div class="flex items-start gap-3">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                                    {{ $step['done'] ? ($i === count($steps) - 1 ? 'bg-emerald-500' : 'bg-blue-600') : 'bg-slate-100' }}">
                                        @if ($step['done'])
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                        @endif
                                    </div>
                                    @if ($i < count($steps) - 1)
                                        <div
                                            class="w-0.5 h-10 mt-1 {{ $steps[$i + 1]['done'] ? 'bg-blue-200' : 'bg-slate-100' }}">
                                        </div>
                                    @endif
                                </div>
                                <div class="{{ $i < count($steps) - 1 ? 'pb-8' : '' }} flex-1">
                                    <p
                                        class="font-black text-sm {{ $step['done'] ? 'text-slate-800' : 'text-slate-300' }}">
                                        {{ $step['label'] }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $step['sub'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- INFO ESTIMASI & PENGIRIMAN --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Info Pengiriman
                        </p>
                        <div class="grid grid-cols-2 gap-3">
                            @if ($order->shippingOption)
                                <div class="bg-indigo-50 rounded-2xl p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400 mb-1">
                                        Kecepatan</p>
                                    <p class="font-black text-slate-800 text-sm">{{ $order->shippingOption->label }}
                                    </p>
                                </div>
                            @endif

                            @if ($order->estimasi_tiba)
                                <div class="bg-emerald-50 rounded-2xl p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-400 mb-1">
                                        Estimasi Tiba</p>
                                    <p class="font-black text-slate-800 text-sm">
                                        {{ $formatRange($order->estimasi_tiba, $order->estimasi_tiba_max) }}
                                    </p>
                                </div>
                            @endif

                            @if ($order->shippingZone)
                                <div class="bg-blue-50 rounded-2xl p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-400 mb-1">Zona
                                    </p>
                                    <p class="font-black text-slate-800 text-sm">{{ $order->shippingZone->nama_zona }}
                                    </p>
                                </div>
                            @endif

                            @if ($order->berat_total_gram)
                                <div class="bg-slate-50 rounded-2xl p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">
                                        Berat Total</p>
                                    <p class="font-black text-slate-800 text-sm">
                                        {{ number_format($order->berat_total_gram, 0, ',', '.') }} gram</p>
                                </div>
                            @endif
                        </div>

                        {{-- Kurir yang diassign --}}
                        @if (in_array($order->status, ['dikirim', 'selesai']) && $order->nama_kurir)
                            <div class="mt-3 bg-blue-50 rounded-2xl p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest text-blue-400 mb-2">Kurir &
                                    Resi</p>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-blue-200 flex items-center justify-center text-blue-700 font-black text-sm shrink-0">
                                        {{ substr($order->nama_kurir, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 text-sm">{{ $order->nama_kurir }}</p>
                                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $order->nomor_resi }}</p>
                                    </div>
                                    @if ($order->dikirim_at)
                                        <div class="ml-auto text-right">
                                            <p class="text-[10px] text-slate-400">Dikirim</p>
                                            <p class="text-xs font-bold text-slate-600">
                                                {{ $order->dikirim_at->format('d M') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ALAMAT PENGIRIMAN --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Alamat
                            Pengiriman</p>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-black text-slate-900">{{ $order->user->name }}</p>
                                <p class="text-sm text-blue-600 font-bold mt-0.5">{{ $order->user->no_telp }}</p>
                                <p class="font-black text-slate-900">{{ $order->nama_penerima ?? $order->user->name }}
                                </p>
                                <p class="text-sm text-blue-600 font-bold mt-0.5">
                                    {{ $order->no_telp_penerima ?? ($order->user->no_telp ?? '-') }}</p>
                                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                    {{ $order->shipping_address ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- PRODUK --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                            Produk Dipesan
                            <span
                                class="ml-2 px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg normal-case text-[10px]">{{ $order->items->count() }}
                                item</span>
                        </p>
                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                @php
                                    $gambar =
                                        $item->product->images->where('variant_id', $item->variant_id)->first()
                                            ?->gambar ?? $item->product->primaryImage?->gambar;
                                @endphp
                                <div class="flex items-center gap-4">
                                    @if ($gambar)
                                        <img src="{{ asset('storage/' . $gambar) }}"
                                            class="w-14 h-14 rounded-2xl object-cover bg-slate-100 shrink-0">
                                    @else
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 shrink-0"></div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-black text-slate-800 text-sm">{{ $item->product->nama_produk }}
                                        </p>
                                        @if ($item->variant)
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $item->variant->nama_varian }}
                                            </p>
                                        @endif
                                        <p class="text-xs text-slate-400">{{ $item->jumlah }}x · Rp
                                            {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-black text-slate-800 text-sm shrink-0">Rp
                                        {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-5">

                    {{-- RINGKASAN BIAYA --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Ringkasan Biaya
                        </p>
                        @php
                            $biayaAdmin = 2500;
                            $subtotal = $order->total_harga - $biayaAdmin - $order->ongkir;
                        @endphp
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Subtotal</span>
                                <span class="font-bold text-slate-800">Rp
                                    {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">
                                    Ongkir
                                    @if ($order->shippingOption && $order->shippingOption->persen_tambahan > 0)
                                        <span
                                            class="text-[10px] text-indigo-400">(+{{ $order->shippingOption->persen_tambahan }}%)</span>
                                    @endif
                                </span>
                                <span class="font-bold text-slate-800">Rp
                                    {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Biaya Layanan</span>
                                <span class="font-bold text-slate-800">Rp
                                    {{ number_format($biayaAdmin, 0, ',', '.') }}</span>
                            </div>
                            <hr class="border-slate-100">
                            <div class="flex justify-between">
                                <span class="font-black text-slate-800">Total</span>
                                <span class="font-black text-blue-600">Rp
                                    {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- METODE PEMBAYARAN --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Metode
                            Pembayaran</p>
                        @if ($order->payment)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm capitalize">
                                        {{ str_replace('_', ' ', $order->payment->metode_pembayaran) }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $order->payment->created_at->format('d M Y • H:i') }} WIB</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-slate-400 font-semibold italic">Belum ada pembayaran</p>
                        @endif
                    </div>

                    {{-- ADMIN: Proses Pengemasan --}}
                    @if (Auth::user()->role === 'admin' && $order->status === 'dibayar')
                        <div class="bg-white rounded-3xl border border-indigo-100 p-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Proses
                                Pesanan</p>
                            <form action="{{ route('orders.update-status', $order->order_id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="dikemas">
                                <button type="submit" onclick="return confirm('Konfirmasi mulai proses pengemasan?')"
                                    class="w-full py-3.5 bg-indigo-600 text-white text-sm font-black rounded-2xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">
                                    Proses Pengemasan
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- ADMIN: Info Kurir (kurir ambil sendiri) --}}
                    @if (Auth::user()->role === 'admin' && $order->status === 'dikemas')
                        <div class="bg-white rounded-3xl border border-orange-100 p-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Status Kurir</p>
                            @if ($order->kurir_id)
                                <div class="flex items-center gap-3 bg-orange-50 rounded-2xl p-4">
                                    <div class="w-10 h-10 rounded-2xl bg-orange-200 flex items-center justify-center text-orange-700 font-black text-sm shrink-0">
                                        {{ substr($order->kurir->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800">{{ $order->kurir->name }}</p>
                                        <p class="text-xs text-orange-500 font-bold mt-0.5">Sedang mengemas paket</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-3 bg-violet-50 rounded-2xl p-4">
                                    <div class="w-10 h-10 rounded-2xl bg-violet-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-violet-700">Menunggu kurir mengambil</p>
                                        <p class="text-xs text-violet-400 mt-0.5">Paket sudah siap diambil kurir</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- PEMBELI: Batalkan --}}
                    @if ($order->user_id == Auth::id() && $order->status === 'dibayar')
                        <div class="bg-white rounded-3xl border border-rose-100 p-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Batalkan
                                Pesanan</p>
                            <div class="bg-blue-50 rounded-2xl p-4 mb-4">
                                <p class="text-xs text-blue-500 font-bold mb-1">Refund ke saldo (70%)</p>
                                <p class="font-black text-blue-700 text-lg">Rp
                                    {{ number_format($order->total_harga * 0.7, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-400 mt-1">Saldo langsung masuk setelah pembatalan.</p>
                            </div>
                            <form action="{{ route('orders.cancel', $order->order_id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin mau batalkan?')"
                                    class="w-full py-3.5 bg-white text-rose-500 text-sm font-black rounded-2xl border border-rose-200 hover:bg-rose-50 transition-all">
                                    Batalkan & Refund ke Saldo
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- PEMBELI: Konfirmasi Diterima --}}
                    @if ($order->user_id == Auth::id() && $order->status === 'dikirim')
                        <form action="{{ route('orders.confirm-delivery', $order->order_id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Konfirmasi pesanan sudah diterima?')"
                                class="w-full py-3.5 bg-emerald-600 text-white text-sm font-black rounded-2xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-100">
                                ✓ Pesanan Sudah Diterima
                            </button>
                        </form>
                    @endif

                    @if ($order->status === 'selesai')
                        <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-5 text-center">
                            <p class="font-black text-emerald-700 text-sm">✓ Transaksi Selesai</p>
                            <p class="text-xs text-emerald-500 mt-1">Terima kasih sudah berbelanja!</p>
                        </div>
                    @elseif($order->status === 'batal')
                        <div class="bg-rose-50 border border-rose-100 rounded-3xl p-5 text-center">
                            <p class="font-black text-rose-600 text-sm">Pesanan Dibatalkan</p>
                            <p class="text-xs text-rose-400 mt-1">Pesanan ini tidak dapat diproses lebih lanjut.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
