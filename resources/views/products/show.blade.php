<x-app-layout>
    @php
        $user = auth()->user();
        $store = $product->store;
        $estimasi = null;

        $shippingService = app(\App\Services\ShippingService::class);
        $opsiReguler = \App\Models\ShippingOption::where('kurang_hari', 0)->first();

        $provinsiPembeli = null;
        if ($user) {
            $alamatDefault = \App\Models\UserAddress::where('user_id', $user->user_id)->where('is_default', 1)->first();
            $provinsiPembeli = $alamatDefault?->province_id ?? $user->province_id;
        }

        if ($provinsiPembeli && $store?->province_id && $opsiReguler) {
            try {
                $zone = $shippingService->tentukanZona((string) $store->province_id, (string) $provinsiPembeli);
                $estimasi = $shippingService->formatEstimasi($zone, $opsiReguler);
            } catch (\Exception $e) { $estimasi = null; }
        }

        if (!$estimasi && $opsiReguler) {
            try {
                $zoneFallback = \App\Models\ShippingZone::where('tipe', 'luar_pulau')->first();
                if ($zoneFallback) $estimasi = $shippingService->formatEstimasi($zoneFallback, $opsiReguler);
            } catch (\Exception $e) { $estimasi = null; }
        }

        $hasVariants = $product->variants->count() > 0;
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .pd-page { font-family: 'Plus Jakarta Sans', sans-serif; }
        .pd-serif { font-family: 'Playfair Display', serif; }

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .pd-vchip-input:checked + .pd-vchip-box {
            border-color: #1C1C1C !important;
            background: #1C1C1C !important;
            color: #fff !important;
        }

        .pd-thumb { border: 2px solid transparent; transition: border-color .2s; }
        .pd-thumb.active { border-color: #1C1C1C !important; }
        .pd-thumb img { opacity: .65; transition: opacity .2s; }
        .pd-thumb.active img, .pd-thumb:hover img { opacity: 1; }

        .pd-main-img img { transition: transform .6s ease; }
        .pd-main-img:hover img { transform: scale(1.06); }

        .pd-btn-buy { transition: background .18s; }
        .pd-btn-buy:hover { background: #C2621A !important; }
        .pd-btn-cart { transition: background .18s; }
        .pd-btn-cart:hover { background: #F5F2EC; }

        .pd-qty-btn:hover { background: #F5F2EC; }
    </style>

    @if (session('success'))
        <div class="max-w-5xl mx-auto mt-4 px-6">
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold">{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-5xl mx-auto mt-4 px-6">
            <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-sm font-semibold">{{ session('error') }}</div>
        </div>
    @endif

    <div class="pd-page" style="background:#FAFAF8; min-height:100vh; padding:28px 20px 64px;">

        {{-- BREADCRUMB --}}
        <div class="max-w-5xl mx-auto mb-6 flex items-center gap-2 text-xs font-medium" style="color:#AAA;">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 font-bold hover:opacity-70 transition-opacity" style="color:#1C1C1C;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <span>/</span>
            <span>{{ $product->category->nama_kategori }}</span>
            <span>/</span>
            <span style="color:#1C1C1C; font-weight:600;">{{ Str::limit($product->nama_produk, 30) }}</span>
        </div>

        {{-- PRODUCT CARD --}}
        <div x-data="{
            qty: 1,
            selectedVariant: '',
            maxStock: {{ $hasVariants ? 0 : $product->stok }},
            price: {{ $hasVariants ? 0 : $product->harga }},
            variantSelected: {{ $hasVariants ? 'false' : 'true' }}
        }" class="max-w-5xl mx-auto bg-white rounded-[28px] border overflow-hidden"
            style="display:grid; grid-template-columns:1.05fr 1fr; border-color:#EBEBEB;">

            {{-- KIRI: GALERI --}}
            <div style="background:#F5F2EC; padding:28px; display:flex; flex-direction:column; gap:14px; position:relative;">

                @php $primary = $product->primaryImage ?? $product->images->first(); @endphp
                <div class="pd-main-img" style="position:relative; width:100%; aspect-ratio:1; border-radius:20px; overflow:hidden; background:#E8E3D8;">
                    <img src="{{ asset('storage/' . ($primary->gambar ?? '')) }}"
                         id="mainImage"
                         style="width:100%; height:100%; object-fit:cover; display:block;"
                         onerror="this.src='https://placehold.co/800x800/E8E3D8/AAAAAA?text=No+Image'">
                    <span style="position:absolute; top:16px; left:16px; background:#1C1C1C; color:#fff; font-size:10px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; padding:5px 13px; border-radius:100px;">
                        {{ $product->category->nama_kategori }}
                    </span>
                    <template x-if="variantSelected && maxStock > 0">
                        <span style="position:absolute; top:16px; right:16px; background:#fff; color:#059669; font-size:10px; font-weight:700; padding:5px 11px; border-radius:100px; border:1px solid #D1FAE5; white-space:nowrap;">
                            Stok: <span x-text="maxStock"></span>
                        </span>
                    </template>
                </div>

                @if ($product->images->count() > 1)
                    <div class="no-scrollbar" style="display:flex; gap:8px; overflow-x:auto; padding-bottom:2px;">
                        @foreach ($product->images as $i => $img)
                            <div class="pd-thumb {{ $i === 0 ? 'active' : '' }}"
                                 style="width:60px; height:60px; border-radius:12px; overflow:hidden; cursor:pointer; flex-shrink:0; background:#DDD8CE;"
                                 onclick="setMain(this, '{{ asset('storage/' . $img->gambar) }}')">
                                <img src="{{ asset('storage/' . $img->gambar) }}"
                                     style="width:100%; height:100%; object-fit:cover; display:block;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- KANAN: DETAIL --}}
            <div style="padding:36px 32px; display:flex; flex-direction:column; border-left:1px solid #EBEBEB;">
                <form id="purchaseForm" action="{{ route('checkout.review') }}" method="GET" style="display:flex; flex-direction:column; flex:1;">
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                    {{-- Store --}}
                    @if ($store)
                        <div style="display:flex; align-items:center; gap:7px; font-size:12px; color:#999; font-weight:500; margin-bottom:14px;">
                            <span style="width:7px; height:7px; border-radius:50%; background:#22C55E; display:inline-block; flex-shrink:0;"></span>
                            {{ $store->nama_toko }}
                            @if ($store->city)
                                <span style="color:#DDD;">·</span>
                                <span style="color:#1C1C1C; font-weight:600;">{{ $store->city }}</span>
                            @endif
                        </div>
                    @endif

                    {{-- Title --}}
                    <h1 class="pd-serif" style="font-size:30px; font-weight:700; line-height:1.2; color:#1C1C1C; letter-spacing:-.01em; margin-bottom:20px;">
                        {{ $product->nama_produk }}
                    </h1>

                    {{-- Price block --}}
                    <div style="background:#F5F2EC; border-radius:16px; padding:16px 18px; display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                        <div>
                            <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#BBB; margin-bottom:4px;">Harga</div>
                            <div style="font-size:26px; font-weight:700; color:#1C1C1C; letter-spacing:-.02em;">
                                <span x-show="variantSelected" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(price)"></span>
                                <span x-show="!variantSelected" style="color:#C0BDB4; font-size:18px; font-weight:500;">Pilih varian dulu</span>
                            </div>
                        </div>
                        <template x-if="variantSelected">
                            <span style="font-size:11px; font-weight:700; color:#059669; background:#D1FAE5; padding:5px 12px; border-radius:100px; flex-shrink:0; white-space:nowrap;">
                                Stok: <span x-text="maxStock"></span>
                            </span>
                        </template>
                    </div>

                    {{-- Shipping --}}
                    <div style="display:flex; align-items:center; gap:12px; padding:14px 0; border-top:1px solid #F0EDE6; border-bottom:1px solid #F0EDE6; margin-bottom:20px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#F0EDE6; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="16" height="16" fill="none" stroke="#888" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#BBB; margin-bottom:3px;">Pengiriman</div>
                            @if ($estimasi)
                                <div style="font-size:13px; font-weight:600; color:#059669;">Estimasi {{ $estimasi }}</div>
                            @else
                                <div style="font-size:13px; font-weight:500; color:#BBB;">Login untuk lihat estimasi</div>
                            @endif
                        </div>
                    </div>

                    {{-- Variants --}}
                    @if ($hasVariants)
                        <div style="margin-bottom:20px;">
                            <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:#BBB; margin-bottom:10px; display:flex; align-items:center; gap:8px;">
                                Pilih Varian
                                <span x-show="selectedVariant" x-text="'— ' + selectedVariant"
                                      style="color:#1C1C1C; font-size:11px; text-transform:none; letter-spacing:0; font-weight:600;"></span>
                            </div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                @foreach ($product->variants as $variant)
                                    <label style="cursor:{{ $variant->stok == 0 ? 'not-allowed' : 'pointer' }};">
                                        <input type="radio" name="variant_id" value="{{ $variant->variant_id }}"
                                               class="pd-vchip-input sr-only"
                                               {{ $variant->stok == 0 ? 'disabled' : '' }}
                                               required
                                               @click="
                                                   price = {{ $product->harga + $variant->harga_tambahan }};
                                                   maxStock = {{ $variant->stok }};
                                                   selectedVariant = '{{ $variant->nama_varian }}';
                                                   variantSelected = true;
                                                   if(qty > maxStock) qty = maxStock;
                                               ">
                                        <div class="pd-vchip-box" style="
                                            padding: 9px 16px;
                                            border-radius: 10px;
                                            border: 1.5px solid {{ $variant->stok == 0 ? '#EEE' : '#E8E4DC' }};
                                            background: #fff;
                                            font-size: 12px;
                                            font-weight: 600;
                                            color: {{ $variant->stok == 0 ? '#BBB' : '#1C1C1C' }};
                                            text-transform: uppercase;
                                            letter-spacing: .04em;
                                            transition: all .15s;
                                            display: flex;
                                            align-items: center;
                                            gap: 6px;
                                            {{ $variant->stok == 0 ? 'text-decoration:line-through; opacity:.4;' : '' }}
                                        ">
                                            {{ $variant->nama_varian }}
                                            @if ($variant->stok == 0)
                                                <span style="font-size:9px; color:#F87171; font-weight:700;">Habis</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Quantity --}}
                    <div x-show="variantSelected" x-transition style="display:flex; align-items:center; gap:14px; margin-bottom:20px;">
                        <div style="display:flex; align-items:center; border:1.5px solid #E8E4DC; border-radius:10px; overflow:hidden;">
                            <button type="button" @click="if(qty > 1) qty--" class="pd-qty-btn"
                                style="width:38px; height:38px; border:none; background:none; font-size:20px; font-weight:300; color:#555; cursor:pointer; line-height:1; transition:background .15s;">
                                −
                            </button>
                            <input type="number" name="quantity" x-model="qty" readonly
                                style="width:40px; text-align:center; background:transparent; border:none; border-left:1px solid #E8E4DC; border-right:1px solid #E8E4DC; font-weight:700; font-size:14px; height:38px; color:#1C1C1C; outline:none;">
                            <button type="button" @click="if(qty < maxStock) qty++" class="pd-qty-btn"
                                style="width:38px; height:38px; border:none; background:none; font-size:20px; font-weight:300; color:#555; cursor:pointer; line-height:1; transition:background .15s;">
                                +
                            </button>
                        </div>
                        <p style="font-size:11px; color:#BBB; line-height:1.55; max-width:180px;">
                            Total biaya & ongkir dihitung di halaman berikutnya.
                        </p>
                    </div>

                    {{-- CTA --}}
                    <div style="margin-top:auto; padding-top:18px; border-top:1px solid #F0EDE6;">
                        <div x-show="variantSelected" x-transition style="display:flex; gap:10px; margin-bottom:12px;">
                            <button type="button" onclick="submitCart()" class="pd-btn-cart"
                                style="flex:1; padding:14px; border-radius:12px; border:2px solid #1C1C1C; background:#fff; color:#1C1C1C; font-weight:700; font-size:12px; cursor:pointer; letter-spacing:.05em; text-transform:uppercase; font-family:'Plus Jakarta Sans',sans-serif;">
                                + Keranjang
                            </button>
                            <button type="submit" class="pd-btn-buy"
                                style="flex:1.5; padding:14px; border-radius:12px; border:none; background:#1C1C1C; color:#fff; font-weight:700; font-size:12px; cursor:pointer; letter-spacing:.05em; text-transform:uppercase; font-family:'Plus Jakarta Sans',sans-serif;">
                                Beli Sekarang →
                            </button>
                        </div>

                        <div x-show="!variantSelected"
                             style="background:#F5F2EC; color:#C0BDB4; padding:14px; border-radius:12px; text-align:center; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.07em; margin-bottom:12px;">
                            Pilih varian terlebih dahulu
                        </div>

                        <p style="text-align:center; font-size:11px; color:#C0BDB4; font-weight:500; display:flex; align-items:center; justify-content:center; gap:6px;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" style="color:#C0BDB4; flex-shrink:0;" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Transaksi aman & terverifikasi oleh MampangPedia
                        </p>
                    </div>
                </form>
            </div>
        </div>

        {{-- REVIEW SECTION --}}
        <div class="max-w-5xl mx-auto mt-6 bg-white rounded-[28px] border overflow-hidden" style="padding:40px; border-color:#EBEBEB;">
            @include('products._review_section', [
                'product'        => $product,
                'reviews'        => $reviews,
                'eligibleOrders' => $eligibleOrders,
            ])
        </div>

    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false, timer: 2000,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            });
        </script>
    @endif

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('snap_token'))
                window.snap.pay("{{ session('snap_token') }}", {
                    onSuccess: function () { window.location.href = "{{ route('orders.history') }}"; },
                    onPending: function () { window.location.href = "{{ route('orders.history') }}"; },
                    onError:   function () { handleCancel(); },
                    onClose:   function () { handleCancel(); }
                });
                function handleCancel() {
                    fetch("{{ route('orders.cancel', session('order_id')) }}", {
                        method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.json())
                    .then(() => {
                        Swal.fire({
                            icon: 'info', title: 'Pesanan Dibatalkan',
                            text: 'Stok sudah dikembalikan.', confirmButtonText: 'Oke'
                        }).then(() => window.location.reload());
                    });
                }
            @endif
        });

        function setMain(thumb, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        function submitCart() {
            const f = document.getElementById('purchaseForm');
            document.getElementById('cartProductId').value = f.querySelector('[name=product_id]').value;
            const v = f.querySelector('[name=variant_id]:checked');
            document.getElementById('cartVariantId').value = v ? v.value : '';
            document.getElementById('cartQty').value = f.querySelector('[name=quantity]').value;
            document.getElementById('cartForm').submit();
        }
    </script>

    <form id="cartForm" action="{{ route('cart.add') }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="product_id" id="cartProductId">
        <input type="hidden" name="variant_id" id="cartVariantId">
        <input type="hidden" name="quantity" id="cartQty">
    </form>

</x-app-layout>
