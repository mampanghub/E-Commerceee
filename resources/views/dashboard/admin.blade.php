<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        {{-- HEADER --}}
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500">Admin Panel</p>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard</h1>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 font-bold">{{ now()->translatedFormat('l, d F Y') }}</p>
                        <p class="text-sm font-black text-slate-600">Halo, {{ auth()->user()->name }} 👋</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                {{-- Pendapatan Layanan Admin --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pendapatan Layanan</p>
                        <p class="text-2xl font-black text-slate-900">Rp {{ number_format($totalCuanAdmin, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400 mt-1">Rp 2.500 × total order</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Pendapatan Produk --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pendapatan Produk</p>
                        <p class="text-2xl font-black text-slate-900">Rp {{ number_format($pendapatanProduk, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400 mt-1">Total nilai semua order</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>

                {{-- Total Ongkir --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Ongkir</p>
                        <p class="text-2xl font-black text-slate-900">Rp {{ number_format($totalOngkir, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400 mt-1">Akumulasi ongkos kirim</p>
                    </div>
                    <div class="w-12 h-12 bg-violet-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                </div>

                {{-- Total Pembeli --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Pembeli</p>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($totalPembeli, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400 mt-1">User yang sudah login</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>

            </div>

            {{-- GRAFIK PENDAPATAN --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Grafik Pendapatan</p>
                        <p class="text-base font-black text-slate-800">7 Bulan Terakhir</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-bold text-slate-400">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Total Pendapatan
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Biaya Layanan
                        </span>
                    </div>
                </div>
                <canvas id="grafikPendapatan" height="100"></canvas>
            </div>

            {{-- ORDER TERBARU --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Order Terbaru</p>
                    <a href="{{ route('orders.index') }}" class="text-xs font-black text-blue-500 hover:text-blue-700 transition-all">
                        Lihat Semua →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($orderTerbaru as $order)
                    @php
                        $statusConfig = [
                            'dibayar' => ['bg-blue-50 text-blue-700',     'Dibayar'],
                            'dikemas' => ['bg-indigo-50 text-indigo-700',  'Dikemas'],
                            'dikirim' => ['bg-violet-50 text-violet-700',  'Dikirim'],
                            'selesai' => ['bg-emerald-50 text-emerald-700','Selesai'],
                            'batal'   => ['bg-rose-50 text-rose-700',      'Batal'],
                        ];
                        [$badgeClass, $statusLabel] = $statusConfig[$order->status] ?? ['bg-slate-50 text-slate-700', $order->status];
                    @endphp
                    <a href="{{ route('orders.show', $order->order_id) }}"
                        class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-black text-slate-800 text-sm">Order #{{ $order->order_id }}</p>
                                <p class="text-xs text-slate-400">{{ $order->user->name }} · {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <p class="font-black text-slate-800 text-sm">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                            <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">Belum ada order.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const grafikData = @json($grafikData);

        const labels      = grafikData.map(d => d.bulan);
        const pendapatan  = grafikData.map(d => d.pendapatan);
        const adminFee    = grafikData.map(d => d.admin_fee);

        new Chart(document.getElementById('grafikPendapatan'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Total Pendapatan',
                        data: pendapatan,
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                    {
                        label: 'Biaya Layanan',
                        data: adminFee,
                        backgroundColor: 'rgba(52, 211, 153, 0.15)',
                        borderColor: 'rgba(52, 211, 153, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: '700', size: 11 }, color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { weight: '700', size: 11 },
                            color: '#94a3b8',
                            callback: val => 'Rp ' + (val / 1000000).toFixed(1) + 'jt'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>