<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        {{-- HEADER --}}
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Admin Panel</p>
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

                <div
                    class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4 hover:shadow-md hover:shadow-slate-100 transition-all">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pendapatan
                            Layanan</p>
                        <p class="text-2xl font-black text-slate-900">Rp
                            {{ number_format($totalCuanAdmin, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400 mt-1">Rp 2.500 × total order</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4 hover:shadow-md hover:shadow-slate-100 transition-all">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pendapatan
                            Produk</p>
                        <p class="text-2xl font-black text-slate-900">Rp
                            {{ number_format($pendapatanProduk, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400 mt-1">Total nilai semua order</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4 hover:shadow-md hover:shadow-slate-100 transition-all">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Ongkir</p>
                        <p class="text-2xl font-black text-slate-900">Rp {{ number_format($totalOngkir, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1">Akumulasi ongkos kirim</p>
                    </div>
                    <div class="w-12 h-12 bg-violet-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl border border-slate-100 p-6 flex items-start justify-between gap-4 hover:shadow-md hover:shadow-slate-100 transition-all">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Pembeli
                        </p>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($totalPembeli, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1">User terdaftar</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

            </div>

            {{-- NOTIF STOK --}}
            @php
                $stokRendah = \App\Models\ProductVariant::with('product')
                    ->where('stok', '<', 5)
                    ->orderBy('stok', 'asc')
                    ->get();
            @endphp

            @if ($stokRendah->count() > 0)
                <div class="bg-white rounded-3xl border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Peringatan
                                Stok</p>
                            <p class="text-base font-black text-slate-800">Varian yang perlu diperhatikan</p>
                        </div>
                        <span class="px-3 py-1 bg-rose-50 text-rose-600 text-xs font-black rounded-xl">
                            {{ $stokRendah->count() }} varian
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                        @foreach ($stokRendah as $variant)
                            @php
                                $color = 'bg-rose-50 border-rose-100';
                                $badge = 'bg-rose-100 text-rose-700';
                                $dot = 'bg-rose-500';
                            @endphp
                            <a href="{{ route('products.edit', $variant->product_id) }}"
                                class="flex items-center justify-between px-4 py-3 rounded-2xl border {{ $color }} hover:opacity-80 transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $dot }} shrink-0"></span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-black text-slate-800 truncate">
                                            {{ $variant->product->nama_produk }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $variant->nama_varian }}</p>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-xl text-xs font-black {{ $badge }} shrink-0 ml-2">
                                    Sisa {{ $variant->stok }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- GRAFIK --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Grafik
                            Pendapatan</p>
                        <p class="text-base font-black text-slate-800">
                            @if ($filterBulan)
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $filterBulan)->translatedFormat('F Y') }}
                            @elseif($filterTahun)
                                Tahun {{ $filterTahun }}
                            @else
                                3 Bulan Terakhir
                            @endif
                        </p>
                    </div>

                    {{-- FILTER AREA --}}
                    <div class="flex items-center gap-2">
                        @if ($filterBulan || $filterTahun)
                            <a href="{{ route('dashboard') }}"
                                class="text-xs font-bold text-slate-400 hover:text-slate-600 px-3 py-2 rounded-xl hover:bg-slate-50 transition-all">
                                ✕ Reset
                            </a>
                        @endif

                        {{-- Custom Dropdown Bulan --}}
                        <div class="relative" id="dropdownBulanWrapper">
                            <button onclick="toggleDropdown('dropdownBulan')"
                                class="px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 flex items-center gap-2 hover:border-blue-500 transition-all">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>
                                    @if (request('bulan'))
                                        {{ collect($filterBulan)->firstWhere('value', request('bulan'))['label'] ?? 'Pilih Bulan' }}
                                    @else
                                        Pilih Bulan
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="dropdownBulan"
                                class="hidden absolute right-0 mt-1 bg-white border border-slate-100 rounded-2xl shadow-lg z-50 overflow-hidden min-w-[160px]">
                                <a href="{{ route('dashboard', array_merge(request()->except('bulan'), ['tahun' => $filterTahun])) }}"
                                    class="block px-4 py-2.5 text-xs font-bold text-slate-400 hover:bg-slate-50">Pilih
                                    Bulan</a>
                                @foreach ($bulanTersedia->sortByDesc('value') as $item)
                                    <a href="{{ route('dashboard', array_merge(request()->except('bulan'), ['bulan' => $item['value']])) }}"
                                        class="block px-4 py-2.5 text-xs font-bold {{ $filterBulan === $item['value'] ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Custom Dropdown Tahun --}}
                        <div class="relative" id="dropdownTahunWrapper">
                            <button type="button" onclick="toggleDropdown('dropdownTahun')"
                                class="flex items-center gap-2 min-w-[120px] text-xs font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 cursor-pointer hover:bg-slate-100 transition-all">
                                <span id="labelTahun">{{ $filterTahun ?? 'Pilih Tahun' }}</span>
                                <svg class="w-3.5 h-3.5 ml-auto text-slate-400 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="dropdownTahun"
                                class="hidden absolute right-0 mt-1 bg-white border border-slate-100 rounded-2xl shadow-lg z-50 overflow-hidden min-w-[120px]">
                                <a href="{{ route('dashboard', array_merge(request()->except('tahun'), ['bulan' => $filterBulan])) }}"
                                    class="block px-4 py-2.5 text-xs font-bold text-slate-400 hover:bg-slate-50">Pilih
                                    Tahun</a>
                                @foreach ($tahunTersedia as $thn)
                                    <a href="{{ route('dashboard', array_merge(request()->except('tahun'), ['tahun' => $thn])) }}"
                                        class="block px-4 py-2.5 text-xs font-bold {{ $filterTahun == $thn ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                        {{ $thn }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <canvas id="grafikPendapatan" height="100"></canvas>
            </div>

            {{-- 2 KOLOM BAWAH --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- STATUS ORDER --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Ringkasan
                            </p>
                            <p class="text-base font-black text-slate-800">Status Pesanan</p>
                        </div>
                        <a href="{{ route('orders.index') }}"
                            class="text-xs font-black text-blue-500 hover:text-blue-700 transition-all">
                            Kelola →
                        </a>
                    </div>
                    @php
                        $statusSummary = [
                            ['label' => 'Dibayar', 'key' => 'dibayar', 'dot' => 'bg-blue-500'],
                            ['label' => 'Dikemas', 'key' => 'dikemas', 'dot' => 'bg-indigo-500'],
                            ['label' => 'Dikirim', 'key' => 'dikirim', 'dot' => 'bg-violet-500'],
                            ['label' => 'Selesai', 'key' => 'selesai', 'dot' => 'bg-emerald-500'],
                            ['label' => 'Dibatalkan', 'key' => 'batal', 'dot' => 'bg-rose-500'],
                        ];
                        $orderCounts = \App\Models\Order::selectRaw('status, count(*) as total')
                            ->groupBy('status')
                            ->pluck('total', 'status');
                        $totalOrders = $orderCounts->sum() ?: 1;
                    @endphp
                    <div class="space-y-3">
                        @foreach ($statusSummary as $s)
                            @php
                                $count = $orderCounts[$s['key']] ?? 0;
                                $pct = round(($count / $totalOrders) * 100);
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full {{ $s['dot'] }} shrink-0"></span>
                                <span
                                    class="text-xs font-bold text-slate-600 w-24 shrink-0">{{ $s['label'] }}</span>
                                <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full {{ $s['dot'] }} transition-all duration-700"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                                <span
                                    class="text-xs font-black text-slate-700 w-8 text-right shrink-0">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs text-slate-400 font-bold">Total semua pesanan</span>
                        <span class="text-sm font-black text-slate-800">{{ $orderCounts->sum() }} pesanan</span>
                    </div>
                </div>

                {{-- PRODUK TERLARIS --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Performa
                            </p>
                            <p class="text-base font-black text-slate-800">Produk Terlaris</p>
                        </div>
                    </div>
                    @php
                        $terlaris = \App\Models\OrderItem::with('product')
                            ->selectRaw('product_id, SUM(jumlah) as total_terjual')
                            ->groupBy('product_id')
                            ->orderByDesc('total_terjual')
                            ->limit(5)
                            ->get();
                    @endphp
                    <div class="space-y-2">
                        @forelse ($terlaris as $i => $item)
                            @php
                                $rankStyle = match ($i) {
                                    0 => 'bg-amber-100 text-amber-600',
                                    1 => 'bg-slate-100 text-slate-500',
                                    2 => 'bg-orange-50 text-orange-400',
                                    default => 'bg-slate-50 text-slate-400',
                                };
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-all">
                                <span
                                    class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black shrink-0 {{ $rankStyle }}">
                                    {{ $i + 1 }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black text-slate-800 truncate">
                                        {{ $item->product->nama_produk ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $item->total_terjual }} terjual</p>
                                </div>
                                <span
                                    class="text-xs font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg shrink-0">
                                    +{{ $item->total_terjual }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-3xl mb-2">📦</p>
                                <p class="text-sm text-slate-400">Belum ada data penjualan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const grafikData = @json($grafikData);
        const labels = grafikData.map(d => d.bulan);
        const pendapatan = grafikData.map(d => d.pendapatan);
        const adminFee = grafikData.map(d => d.admin_fee);

        new Chart(document.getElementById('grafikPendapatan'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                        label: 'Total Pendapatan',
                        data: pendapatan,
                        backgroundColor: 'rgba(59, 130, 246, 0.08)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2.5,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Biaya Layanan',
                        data: adminFee,
                        backgroundColor: 'rgba(52, 211, 153, 0.08)',
                        borderColor: 'rgba(52, 211, 153, 1)',
                        borderWidth: 2.5,
                        pointBackgroundColor: 'rgba(52, 211, 153, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                weight: '700',
                                size: 11
                            },
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                weight: '700',
                                size: 11
                            },
                            color: '#94a3b8',
                            callback: val => {
                                if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                                if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                                return 'Rp ' + val;
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        function toggleDropdown(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function(e) {
            ['dropdownBulan', 'dropdownTahun'].forEach(id => {
                const wrapper = document.getElementById(id + 'Wrapper');
                const dropdown = document.getElementById(id);
                if (wrapper && !wrapper.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
