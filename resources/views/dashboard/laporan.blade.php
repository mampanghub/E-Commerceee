<x-app-layout>
<div class="min-h-screen bg-slate-100">

    {{-- HEADER --}}
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Manajemen</p>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight">Laporan Transaksi</h1>
                    </div>
                </div>
                <button onclick="window.print()"
                    class="no-print inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-black hover:bg-blue-600 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak / Export PDF
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.laporan') }}"
            class="bg-white rounded-2xl border border-slate-200 p-5 no-print">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Filter Tanggal</p>
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Dari</label>
                    <input type="date" name="dari" value="{{ request('dari') }}"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Sampai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-black hover:bg-blue-700 transition-all">
                    Tampilkan
                </button>
                @if(request('dari') || request('sampai'))
                <a href="{{ route('admin.laporan') }}"
                    class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-black hover:bg-slate-200 transition-all">
                    Reset
                </a>
                @endif
            </div>
            @if(request('dari') && request('sampai'))
            <p class="text-xs text-blue-500 font-bold mt-3">
                Menampilkan {{ \Carbon\Carbon::parse(request('dari'))->translatedFormat('d F Y') }}
                — {{ \Carbon\Carbon::parse(request('sampai'))->translatedFormat('d F Y') }}
            </p>
            @endif
        </form>

        {{-- STAT CARDS --}}
        @php
            $grandTotal  = $orders->sum('total_harga');
            $totalFee    = $orders->count() * 2500;
            $totalOngkir = $orders->sum('ongkir');
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Transaksi</p>
                <p class="text-2xl font-black text-slate-900">{{ $orders->count() }}</p>
                <p class="text-xs text-slate-400 mt-1">order tercatat</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Gross Revenue</p>
                <p class="text-xl font-black text-slate-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-1">total pendapatan</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Fee Admin</p>
                <p class="text-xl font-black text-blue-600">Rp {{ number_format($totalFee, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-1">Rp 2.500 × {{ $orders->count() }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Ongkir</p>
                <p class="text-xl font-black text-slate-900">Rp {{ number_format($totalOngkir, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-1">akumulasi ongkos kirim</p>
            </div>
        </div>

        {{-- BREAKDOWN STATUS --}}
        @php
            $statusConfig = [
                'dibayar' => ['label' => 'Dibayar', 'color' => 'bg-blue-500'],
                'dikemas' => ['label' => 'Dikemas', 'color' => 'bg-indigo-500'],
                'dikirim' => ['label' => 'Dikirim', 'color' => 'bg-violet-500'],
                'selesai' => ['label' => 'Selesai', 'color' => 'bg-emerald-500'],
                'batal'   => ['label' => 'Batal',   'color' => 'bg-rose-400'],
            ];
            $totalAllStatus = $statusBreakdown->sum();
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-5">Breakdown Status Order</p>
            <div class="flex flex-col gap-3">
                @foreach($statusConfig as $key => $cfg)
                @php $jumlah = $statusBreakdown[$key] ?? 0; $pct = $totalAllStatus > 0 ? round($jumlah / $totalAllStatus * 100) : 0; @endphp
                <div class="flex items-center gap-4">
                    <span class="text-xs font-black text-slate-600 w-20 shrink-0">{{ $cfg['label'] }}</span>
                    <div class="flex-1 bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="{{ $cfg['color'] }} h-2.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-xs font-black text-slate-500 w-16 text-right shrink-0">{{ $jumlah }} ({{ $pct }}%)</span>
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- PRODUK TERLARIS --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <p class="text-sm font-black text-slate-700">🏆 Produk Terlaris</p>
            </div>
            @if($produkTerlaris->isEmpty())
            <div class="px-6 py-10 text-center text-slate-400 text-sm font-bold">Belum ada data penjualan.</div>
            @else
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">#</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Produk</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Terjual</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($produkTerlaris as $i => $item)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-6 py-4 text-xs font-black text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->product->primaryImage ? asset('storage/'.$item->product->primaryImage->gambar) : 'https://placehold.co/40x40?text=?' }}"
                                    class="w-10 h-10 rounded-xl object-cover bg-slate-100"
                                    onerror="this.src='https://placehold.co/40x40?text=?'">
                                <span class="text-sm font-black text-slate-800">{{ $item->product->nama_produk ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-emerald-600">{{ $item->total_terjual }} pcs</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-slate-900">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- TABEL TRANSAKSI --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <p class="text-sm font-black text-slate-700">
                    Rincian Transaksi
                    <span class="ml-2 px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold">
                        {{ $orders->count() }} data
                    </span>
                </p>
                <p class="text-xs text-slate-400">{{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>

            @if($orders->isEmpty())
            <div class="py-16 text-center">
                <p class="text-4xl mb-3">📭</p>
                <p class="text-sm font-black text-slate-500">Tidak ada transaksi ditemukan.</p>
                @if(request('dari') || request('sampai'))
                <a href="{{ route('admin.laporan') }}" class="mt-3 inline-block text-xs font-black text-blue-500 hover:underline">Reset filter</a>
                @endif
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Order</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pembeli</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Toko</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ongkir</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Fee</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Total</th>
                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($orders as $i => $order)
                        @php
                            $sc = [
                                'dibayar' => 'bg-blue-50 text-blue-700',
                                'dikemas' => 'bg-indigo-50 text-indigo-700',
                                'dikirim' => 'bg-violet-50 text-violet-700',
                                'selesai' => 'bg-emerald-50 text-emerald-700',
                                'batal'   => 'bg-rose-50 text-rose-600',
                            ];
                            $badgeClass = $sc[$order->status] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 text-xs text-slate-400 font-bold">{{ $i + 1 }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.show', $order->order_id) }}"
                                    class="font-black text-blue-600 text-sm hover:underline">#{{ $order->order_id }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700">{{ $order->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $order->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 bg-slate-200 rounded-lg flex items-center justify-center text-slate-600 text-[10px] font-black shrink-0">
                                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-800">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $order->items->first()->product->store->nama_toko ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-slate-700 whitespace-nowrap">
                                Rp {{ number_format($order->ongkir, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-blue-600 whitespace-nowrap">
                                Rp 2.500
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-black text-slate-900 whitespace-nowrap">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer total --}}
            <div class="px-6 py-5 bg-slate-50 border-t border-slate-200 flex justify-end">
                <div class="flex items-center gap-8 px-6 py-4 bg-white rounded-xl border border-slate-200">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Fee Admin</p>
                        <p class="text-lg font-black text-blue-600">Rp {{ number_format($totalFee, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-px h-10 bg-slate-200"></div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Gross</p>
                        <p class="text-lg font-black text-slate-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikRevenue');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($grafikData->pluck('bulan')) !!},
                datasets: [
                    {
                        label: 'Revenue (Rp)',
                        data: {!! json_encode($grafikData->pluck('pendapatan')) !!},
                        backgroundColor: 'rgba(99, 102, 241, 0.15)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 2,
                        borderRadius: 8,
                        type: 'bar',
                    },
                    {
                        label: 'Jumlah Order',
                        data: {!! json_encode($grafikData->pluck('jumlah')) !!},
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        type: 'line',
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'top' } },
                scales: {
                    y:  { ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } },
                    y1: { position: 'right', grid: { drawOnChartArea: false }, ticks: { callback: v => v + ' order' } }
                }
            }
        });
    }
</script>

<style>
    @media print {
        .no-print, nav, header { display: none !important; }
        body { background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .max-w-6xl { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .rounded-2xl { border-radius: 0 !important; }
    }
</style>
</x-app-layout>
