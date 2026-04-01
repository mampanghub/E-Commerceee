<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Pesanan Saya</h2>
                <p class="text-sm text-slate-500 mt-1">Pantau dan kelola semua transaksi kamu di sini.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-white border-l-4 border-emerald-500 shadow-sm rounded-r-xl flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="ml-4 text-sm font-semibold text-slate-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- TAB NAVIGATION --}}
            <div class="flex gap-1.5 bg-white border border-slate-100 p-1.5 rounded-2xl shadow-sm mb-6 overflow-x-auto">
                @php
                    $tabs = [
                        'semua' => ['label' => 'Semua', 'count' => array_sum($counts)],
                        'dibayar' => ['label' => 'Dibayar', 'count' => $counts['dibayar']],
                        'dikemas' => ['label' => 'Dikemas', 'count' => $counts['dikemas']],
                        'dikirim' => ['label' => 'Dikirim', 'count' => $counts['dikirim']],
                        'selesai' => ['label' => 'Selesai', 'count' => $counts['selesai']],
                    ];
                    $activeTab = request('tab', 'dibayar');
                    $tabStyles = [
                        'semua' => 'bg-slate-800 text-white shadow',
                        'dibayar' => 'bg-blue-600 text-white shadow',
                        'dikemas' => 'bg-indigo-600 text-white shadow',
                        'dikirim' => 'bg-violet-600 text-white shadow',
                        'selesai' => 'bg-emerald-600 text-white shadow',
                        'batal' => 'bg-rose-500 text-white shadow',
                    ];
                @endphp

                @foreach ($tabs as $key => $tab)
                    <a href="{{ route('orders.history', ['tab' => $key]) }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest whitespace-nowrap transition-all duration-200
                            {{ $activeTab === $key ? $tabStyles[$key] : 'text-slate-400 hover:text-slate-700' }}">
                        {{ $tab['label'] }}
                        @if ($tab['count'] > 0)
                            <span
                                class="px-1.5 py-0.5 rounded-lg text-[10px] font-black
                                        {{ $activeTab === $key ? 'bg-white/20' : 'bg-slate-100 text-slate-500' }}">
                                {{ $tab['count'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- EMPTY STATE --}}
            @if ($orders->isEmpty())
                <div class="bg-white border border-slate-100 rounded-3xl py-20 px-4 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 mb-1">Belum ada pesanan</h3>
                    <p class="text-slate-400 text-sm mb-6">Pesanan dengan status ini belum ada.</p>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-6 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all text-sm">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        @php
                            $statusConfig = [
                                'dibayar' => ['bg-blue-50 text-blue-700 border-blue-200', 'Dibayar', 'border-blue-100'],
                                'dikemas' => [
                                    'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'Sedang Dikemas',
                                    'border-indigo-100',
                                ],
                                'dikirim' => [
                                    'bg-violet-50 text-violet-700 border-violet-200',
                                    'Sedang Dikirim',
                                    'border-violet-100',
                                ],
                                'selesai' => [
                                    'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Selesai',
                                    'border-emerald-100',
                                ],
                            ];
                            [$badgeClass, $statusLabel, $cardBorder] = $statusConfig[$order->status] ?? [
                                'bg-slate-50 text-slate-700 border-slate-100',
                                $order->status,
                                'border-slate-100',
                            ];
                        @endphp

                        <div
                            class="bg-white rounded-3xl border {{ $cardBorder }} shadow-sm hover:shadow-md transition-shadow overflow-hidden">

                            {{-- HEADER CARD --}}
                            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="font-mono font-black text-slate-700 text-sm">#{{ $order->order_id }}</span>
                                    <span class="text-slate-300">·</span>
                                    <span class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}
                                        WIB</span>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            {{-- PRODUK PREVIEW --}}
                            <div class="px-6 py-4">
                                @php $previewItems = $order->items->take(2); @endphp
                                <div class="space-y-3">
                                    @foreach ($previewItems as $item)
                                        <div class="flex items-center gap-3">
                                            {{-- FIX: prioritaskan foto varian --}}
                                            @php
                                                $fotoVarian = $item->variant_id
                                                    ? $item->product->images
                                                        ->where('variant_id', $item->variant_id)
                                                        ->first()
                                                    : null;
                                                $gambarItem =
                                                    $fotoVarian?->gambar ?? $item->product->primaryImage?->gambar;
                                            @endphp
                                            @if ($gambarItem)
                                                <img src="{{ Storage::url($gambarItem) }}"
                                                    class="w-12 h-12 rounded-2xl object-cover bg-slate-100 shrink-0">
                                            @else
                                                <div class="w-12 h-12 rounded-2xl bg-slate-100 shrink-0"></div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-slate-800 text-sm truncate">
                                                    {{ $item->product->nama_produk }}</p>
                                                @if ($item->variant)
                                                    <p class="text-xs text-slate-400">{{ $item->variant->nama_varian }}
                                                        ·
                                                        {{ $item->jumlah }}x</p>
                                                @else
                                                    <p class="text-xs text-slate-400">{{ $item->jumlah }}x</p>
                                                @endif
                                            </div>
                                            <p class="text-sm font-black text-slate-800 shrink-0">Rp
                                                {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                    @if ($order->items->count() > 2)
                                        <p class="text-xs text-slate-400 font-semibold">
                                            +{{ $order->items->count() - 2 }} produk
                                            lainnya</p>
                                    @endif
                                </div>
                            </div>

                            {{-- FOOTER CARD --}}
                            <div
                                class="flex items-center justify-between px-6 py-4 bg-slate-50/50 border-t border-slate-50">
                                <div>
                                    <span class="text-xs text-slate-400 font-semibold">Total Pembayaran</span>
                                    <p class="font-black text-slate-900 text-base">Rp
                                        {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-2">

                                    {{-- Invoice — tampil untuk semua status kecuali menunggu --}}
                                    @if ($order->status !== 'menunggu')
                                        <a href="{{ route('orders.invoice', $order->order_id) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-black text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Invoice
                                        </a>
                                    @endif

                                    {{-- Konfirmasi diterima --}}
                                    @if ($order->status === 'dikirim')
                                        <form id="confirm-form-{{ $order->order_id }}"
                                            action="{{ route('orders.confirm-delivery', $order->order_id) }}"
                                            method="POST" style="display:none">
                                            @csrf
                                        </form>
                                        <button type="button" onclick="konfirmasiDiterima({{ $order->order_id }})"
                                            class="px-4 py-2 bg-emerald-600 text-white text-xs font-black rounded-xl hover:bg-emerald-700 transition-all">
                                            ✓ Pesanan Diterima
                                        </button>
                                    @endif

                                    <a href="{{ route('orders.show', $order->order_id) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-black text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-all">
                                        Lihat Detail
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($orders->hasPages())
                    <div class="mt-8">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
    <script>
        function konfirmasiDiterima(orderId) {
            Swal.fire({
                title: 'Pesanan Sudah Diterima?',
                text: 'Pastikan barangnya udah sampai ya!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Iya, Sudah Diterima',
                cancelButtonText: 'Belum',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#94a3b8',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('confirm-form-' + orderId).submit();
                }
            });
        }
    </script>

    @if (session('show_review_popup'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const starLabels = ['', 'Buruk', 'Kurang', 'Cukup', 'Bagus', 'Sangat Bagus'];
                let selectedStar = 0;

                Swal.fire({
                    title: '⭐ Kasih Ulasan Yuk!',
                    html: `
            <p style="color:#94a3b8;font-size:13px;margin-bottom:16px;">
                Gimana pengalamanmu dengan produk ini?
            </p>
            <div id="swal-stars" style="display:flex;justify-content:center;gap:8px;margin-bottom:8px;">
                ${[1,2,3,4,5].map(i => `
                            <svg data-val="${i}" class="swal-star"
                                style="width:40px;height:40px;color:#e5e7eb;cursor:pointer;transition:color .15s;"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        `).join('')}
            </div>
            <p id="swal-star-label" style="font-size:12px;color:#94a3b8;margin-bottom:12px;">
                Pilih bintang dulu
            </p>
            <textarea id="swal-komentar" rows="3"
                placeholder="Ceritakan pengalamanmu (opsional)..."
                style="width:100%;border:1px solid #e2e8f0;border-radius:12px;padding:10px 12px;font-size:13px;resize:none;outline:none;box-sizing:border-box;"></textarea>
        `,
                    showCancelButton: true,
                    confirmButtonText: 'Kirim Ulasan',
                    cancelButtonText: 'Nanti Aja',
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#94a3b8',
                    customClass: {
                        popup: 'rounded-[2rem]'
                    },
                    didOpen: () => {
                        const stars = document.querySelectorAll('.swal-star');
                        const paintStars = (upTo) => {
                            stars.forEach((s, i) => s.style.color = i < upTo ? '#fbbf24' : '#e5e7eb');
                        };
                        stars.forEach(star => {
                            star.addEventListener('click', () => {
                                selectedStar = parseInt(star.dataset.val);
                                paintStars(selectedStar);
                                document.getElementById('swal-star-label').textContent =
                                    starLabels[selectedStar];
                            });
                            star.addEventListener('mouseenter', () => paintStars(parseInt(star
                                .dataset.val)));
                            star.addEventListener('mouseleave', () => paintStars(selectedStar));
                        });
                    },
                    preConfirm: () => {
                        if (selectedStar === 0) {
                            Swal.showValidationMessage('Pilih bintang dulu ya!');
                            return false;
                        }
                        return {
                            bintang: selectedStar,
                            komentar: document.getElementById('swal-komentar').value
                        };
                    }
                }).then(result => {
                    if (!result.isConfirmed) return;

                    const form = new FormData();
                    form.append('_token', '{{ csrf_token() }}');
                    form.append('order_id', '{{ session('review_order_id') }}');
                    form.append('bintang', result.value.bintang);
                    form.append('komentar', result.value.komentar);

                    fetch('{{ route('reviews.store', session('review_product_id') ?? 0) }}', {
                            method: 'POST',
                            body: form
                        })
                        .then(r => {
                            if (!r.ok) throw new Error();
                        })
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Makasih ulasannya! 🎉',
                                showConfirmButton: false,
                                timer: 2000,
                                customClass: {
                                    popup: 'rounded-[2rem]'
                                }
                            }).then(() => window.location.reload());
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Kirim',
                                text: 'Coba lagi ya.',
                                customClass: {
                                    popup: 'rounded-[2rem]'
                                }
                            });
                        });
                });
            });
        </script>
    @endif
</x-app-layout>
