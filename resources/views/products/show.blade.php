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
            } catch (\Exception $e) {
                $estimasi = null;
            }
        }

        if (!$estimasi && $opsiReguler) {
            try {
                $zoneFallback = \App\Models\ShippingZone::where('tipe', 'luar_pulau')->first();
                if ($zoneFallback) {
                    $estimasi = $shippingService->formatEstimasi($zoneFallback, $opsiReguler);
                }
            } catch (\Exception $e) {
                $estimasi = null;
            }
        }

        $hasVariants = $product->variants->count() > 0;
        $isWishlisted = auth()->check()
            ? \App\Models\Wishlist::where('user_id', auth()->user()->user_id)
                ->where('product_id', $product->product_id)
                ->exists()
            : false;
        $primaryImage = $product->primaryImage ?? $product->images->first();

        // Buat map variant_id => gambar URL untuk JS
        $variantImages = [];
        foreach ($product->variants as $variant) {
            $varImg = $product->images->where('variant_id', $variant->variant_id)->first();
            $varImg = $varImg ?? $primaryImage;
            $variantImages[$variant->variant_id] = $varImg ? asset('storage/' . $varImg->gambar) : 'https://placehold.co/600x600/f5f5f5/aaa?text=No+Image';
        }
    @endphp

    <style>
        .pd-page * {
            box-sizing: border-box;
        }

        .pd-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f4ff;
            padding: 16px;
            min-height: 100vh;
        }

        /* BREADCRUMB */
        .pd-bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #888;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .pd-bc a {
            color: #2563EB;
            text-decoration: none;
        }

        .pd-bc a:hover {
            text-decoration: underline;
        }

        /* 3-COL LAYOUT */
        .pd-layout {
            display: grid;
            grid-template-columns: 300px 1fr 268px;
            gap: 12px;
            align-items: start;
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ── KIRI: GALERI ── */
        .pd-col-gallery {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dbeafe;
        }

        .pd-main-wrap {
            width: 100%;
            aspect-ratio: 1;
            background: #fafafa;
            overflow: hidden;
        }

        .pd-main-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
            transition: transform .4s ease;
        }

        .pd-main-wrap:hover img {
            transform: scale(1.05);
        }

        .pd-thumbs {
            display: flex;
            border-top: 1px solid #f0f0f0;
        }

        .pd-thumb {
            flex: 1;
            aspect-ratio: 1;
            cursor: pointer;
            border-right: 1px solid #f0f0f0;
            background: #fff;
            position: relative;
            overflow: hidden;
        }

        .pd-thumb:last-child {
            border-right: none;
        }

        .pd-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
            transition: opacity .15s;
        }

        .pd-thumb:hover img {
            opacity: .7;
        }

        .pd-thumb.active {
            border-bottom: 2.5px solid #2563EB;
        }

        /* ── TENGAH: INFO ── */
        .pd-col-info {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dbeafe;
        }

        .pd-prod-name {
            font-size: 15px;
            font-weight: 400;
            color: #111;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .pd-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #888;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .pd-sold {
            background: #eff6ff;
            color: #2563EB;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }

        .pd-stars {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .pd-star {
            color: #F59E0B;
            font-size: 13px;
        }

        .pd-rnum {
            font-weight: 500;
            color: #333;
            font-size: 12px;
        }

        .pd-rct {
            color: #aaa;
            font-size: 11px;
        }

        .pd-dot {
            color: #ddd;
        }

        .pd-price {
            font-size: 26px;
            font-weight: 700;
            color: #1d4ed8;
            margin-bottom: 16px;
            letter-spacing: -.01em;
        }

        .pd-divider {
            height: 1px;
            background: #eff6ff;
            margin: 14px 0;
        }

        .pd-v-label {
            font-size: 13px;
            color: #555;
            margin-bottom: 10px;
        }

        .pd-v-label b {
            color: #2563EB;
            font-weight: 500;
        }

        .pd-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .pd-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 13px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #fff;
            font-size: 13px;
            color: #333;
            cursor: pointer;
            transition: all .12s;
            user-select: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .pd-chip:hover {
            border-color: #2563EB;
            color: #2563EB;
        }

        .pd-chip.active {
            border-color: #2563EB;
            color: #2563EB;
            background: #eff6ff;
            font-weight: 500;
        }

        .pd-chip.disabled {
            opacity: .4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pd-swatch {
            width: 14px;
            height: 14px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .pd-sz {
            width: 46px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 13px;
            color: #333;
            cursor: pointer;
            transition: all .12s;
            user-select: none;
        }

        .pd-sz:hover {
            border-color: #2563EB;
            color: #2563EB;
        }

        .pd-sz.active {
            border-color: #2563EB;
            color: #2563EB;
            background: #eff6ff;
            font-weight: 500;
        }

        .pd-sz.disabled {
            opacity: .4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pd-ship-box {
            background: #f8faff;
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #dbeafe;
            margin-top: 4px;
        }

        .pd-ship-title {
            font-size: 11px;
            font-weight: 500;
            color: #888;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 8px;
        }

        .pd-ship-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pd-ship-svc {
            font-size: 12px;
            font-weight: 500;
            color: #222;
        }

        .pd-ship-est {
            font-size: 11px;
            color: #888;
        }

        .pd-ship-price {
            margin-left: auto;
            font-size: 12px;
            font-weight: 600;
            color: #2563EB;
        }

        /* ── KANAN: AKSI ── */
        .pd-col-action {
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: sticky;
            top: 16px;
        }

        .pd-action-card {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #dbeafe;
        }

        .pd-action-title {
            font-size: 13px;
            font-weight: 500;
            color: #111;
            margin-bottom: 12px;
        }

        .pd-sel-var {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: border-color .15s;
        }

        .pd-sel-var:hover {
            border-color: #2563EB;
        }

        .pd-sel-img {
            width: 38px;
            height: 38px;
            border-radius: 4px;
            background: #eff6ff;
            object-fit: contain;
            flex-shrink: 0;
            transition: opacity .2s ease;
        }

        .pd-sel-name {
            font-size: 13px;
            color: #222;
            flex: 1;
        }

        .pd-sel-line {
            height: 2px;
            width: 28px;
            background: #2563EB;
            border-radius: 1px;
            margin-top: 3px;
        }

        .pd-sel-arrow {
            font-size: 16px;
            color: #aaa;
        }

        .pd-qty-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .pd-qty-lbl {
            font-size: 13px;
            color: #555;
        }

        .pd-qty-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pd-qty-ctrl {
            display: flex;
            align-items: center;
            border: 1px solid #dbeafe;
            border-radius: 6px;
            overflow: hidden;
        }

        .pd-qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #fff;
            font-size: 18px;
            font-weight: 300;
            color: #2563EB;
            cursor: pointer;
            line-height: 1;
            transition: background .1s;
            font-family: inherit;
        }

        .pd-qty-btn:hover {
            background: #eff6ff;
        }

        .pd-qty-num {
            width: 40px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            color: #111;
            border-left: 1px solid #dbeafe;
            border-right: 1px solid #dbeafe;
            height: 32px;
            line-height: 32px;
        }

        .pd-stok {
            font-size: 12px;
            color: #555;
        }

        .pd-stok b {
            color: #2563EB;
        }

        .pd-subtotal-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 1px solid #eff6ff;
            margin-bottom: 14px;
        }

        .pd-sub-lbl {
            font-size: 13px;
            color: #555;
        }

        .pd-sub-val {
            font-size: 20px;
            font-weight: 700;
            color: #1d4ed8;
        }

        .pd-btn-cart {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: #2563EB;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background .15s;
        }

        .pd-btn-cart:hover {
            background: #1d4ed8;
        }

        .pd-btn-buy {
            width: 100%;
            padding: 11px;
            border-radius: 8px;
            border: 1.5px solid #2563EB;
            background: #fff;
            color: #2563EB;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background .15s;
        }

        .pd-btn-buy:hover {
            background: #eff6ff;
        }

        .pd-btn-disabled {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #eee;
            background: #f9f9f9;
            color: #aaa;
            font-size: 13px;
            font-weight: 500;
            cursor: not-allowed;
            margin-bottom: 8px;
            text-align: center;
        }

        .pd-links-card {
            background: #fff;
            border-radius: 8px;
            padding: 10px 16px;
            border: 1px solid #dbeafe;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .pd-alink {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #555;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            transition: background .12s;
            text-decoration: none;
        }

        .pd-alink:hover {
            background: #eff6ff;
        }

        .pd-alink svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        /* REVIEW SECTION */
        .pd-review-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dbeafe;
            max-width: 1100px;
            margin: 12px auto 0;
        }

        /* Store dot */
        .store-dot {
            background: #2563EB !important;
        }

        /* Ship price green -> blue */
        .shipping-price-blue {
            color: #2563EB !important;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .pd-layout {
                grid-template-columns: 260px 1fr;
            }

            .pd-col-action {
                grid-column: 1 / -1;
                position: static;
            }
        }

        @media (max-width: 640px) {
            .pd-layout {
                grid-template-columns: 1fr;
            }

            .pd-col-action {
                grid-column: 1;
            }
        }

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @if (session('success'))
        <div style="max-width:1100px; margin:0 auto 12px; padding:0 0;">
            <div
                style="padding:12px 16px; background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8; border-radius:8px; font-size:13px; font-weight:500;">
                {{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div style="max-width:1100px; margin:0 auto 12px;">
            <div
                style="padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:8px; font-size:13px; font-weight:500;">
                {{ session('error') }}</div>
        </div>
    @endif

    {{-- Variant images map for JS --}}
    <script>
        const pdVariantImages = @json($variantImages);
        const pdDefaultImage = "{{ asset('storage/' . ($primaryImage->gambar ?? '')) }}";
    </script>

    <div class="pd-page" x-data="{
        qty: 1,
        selectedVariant: '',
        maxStock: {{ $hasVariants ? 0 : $product->stok }},
        price: {{ $hasVariants ? $product->harga ?? 0 : $product->harga }},
        variantSelected: {{ $hasVariants ? 'false' : 'true' }},
        get subtotal() { return this.price * this.qty; },
        get formattedPrice() { return 'Rp' + new Intl.NumberFormat('id-ID').format(this.price); },
        get formattedSubtotal() { return 'Rp' + new Intl.NumberFormat('id-ID').format(this.subtotal); }
    }">

        {{-- BACK BUTTON + BREADCRUMB --}}
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px; flex-wrap:wrap;">
            <button onclick="history.back()"
                style="display:inline-flex; align-items:center; gap:6px;
                       padding:6px 14px 6px 10px;
                       background:#fff; border:1px solid #dbeafe;
                       border-radius:8px; font-size:13px; font-weight:500;
                       color:#2563EB; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif;
                       transition:background .12s, box-shadow .12s;
                       box-shadow:0 1px 3px rgba(37,99,235,.07);"
                onmouseover="this.style.background='#eff6ff'; this.style.boxShadow='0 2px 8px rgba(37,99,235,.13)';"
                onmouseout="this.style.background='#fff'; this.style.boxShadow='0 1px 3px rgba(37,99,235,.07)';">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>

            <div class="pd-bc" style="margin-bottom:0;">
                <a href="{{ route('dashboard') }}">Home</a>
                <span>›</span>
                <a href="#">{{ $product->category->nama_kategori }}</a>
                <span>›</span>
                <span style="color:#555;">{{ Str::limit($product->nama_produk, 40) }}</span>
            </div>
        </div>

        {{-- 3-COLUMN LAYOUT --}}
        <div class="pd-layout">

            {{-- KIRI: GALERI --}}
            <div class="pd-col-gallery">
                <div class="pd-main-wrap">
                    <img id="pdMainImg" src="{{ asset('storage/' . ($primaryImage->gambar ?? '')) }}"
                        onerror="this.src='https://placehold.co/600x600/f5f5f5/aaa?text=No+Image'"
                        alt="{{ $product->nama_produk }}">
                </div>

                @if ($product->images->count() > 1)
                    <div class="pd-thumbs">
                        @foreach ($product->images->take(5) as $i => $img)
                            <div class="pd-thumb {{ $i === 0 ? 'active' : '' }}"
                                onclick="pdSetMain(this, '{{ asset('storage/' . $img->gambar) }}')">
                                <img src="{{ asset('storage/' . $img->gambar) }}"
                                    onerror="this.src='https://placehold.co/100x100/f5f5f5/aaa?text=?'"
                                    alt="foto {{ $i + 1 }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- TENGAH: INFO PRODUK --}}
            <div class="pd-col-info">
                {{-- Store --}}
                @if ($store)
                    <div
                        style="display:flex; align-items:center; gap:6px; font-size:12px; color:#888; margin-bottom:10px; flex-wrap:wrap;">
                        <span
                            style="width:7px; height:7px; border-radius:50%; background:#2563EB; display:inline-block;"></span>
                        <span style="font-weight:500; color:#333;">{{ $store->nama_toko }}</span>
                        @if ($store->city)
                            <span style="color:#ddd;">·</span>
                            <span>{{ $store->city }}</span>
                        @endif
                    </div>
                @endif

                <div class="pd-prod-name">{{ $product->nama_produk }}</div>

                @php
                    $avgRating = round($reviews->avg('bintang') ?? 0, 1);
                    $totalReviews = $reviews->count();
                    $fullStars = floor($avgRating);
                    $halfStar = $avgRating - $fullStars >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - $fullStars - $halfStar;
                    $totalTerjual =
                        $product
                            ->orderItems()
                            ->whereHas('order', fn($q) => $q->whereIn('status', ['selesai', 'dikirim', 'diproses']))
                            ->sum('jumlah') ?? 0;

                    $allShippingOptions = \App\Models\ShippingOption::orderBy('kurang_hari', 'asc')->get();
                    $shippingRows = [];

                    if ($provinsiPembeli && $store?->province_id) {
                        foreach ($allShippingOptions as $opt) {
                            try {
                                $zone = $shippingService->tentukanZona(
                                    (string) $store->province_id,
                                    (string) $provinsiPembeli,
                                );
                                $ongkirDsr = $shippingService->hitungOngkirDasar(
                                    (string) $store->province_id,
                                    (string) $provinsiPembeli,
                                    $product->berat ?? 500,
                                );
                                $ongkirFnl = $shippingService->hitungOngkirFinal($ongkirDsr, $opt);
                                $est = $shippingService->formatEstimasi($zone, $opt);
                                $tiba = $shippingService->formatTanggalTiba($zone, $opt);
                                $shippingRows[] = [
                                    'label' => $opt->label,
                                    'harga' => $ongkirFnl,
                                    'est' => $est,
                                    'tiba' => $tiba,
                                ];
                            } catch (\Exception $e) {
                            }
                        }
                    }
                @endphp

                <div class="pd-meta">
                    @if ($totalTerjual > 0)
                        <span class="pd-sold">Terjual
                            @if ($totalTerjual >= 1000)
                                {{ floor($totalTerjual / 1000) }}rb+
                            @else
                                {{ $totalTerjual }}
                            @endif
                        </span>
                        <span class="pd-dot">•</span>
                    @endif

                    @if ($totalReviews > 0)
                        <span class="pd-stars" style="display:flex; align-items:center; gap:3px;">
                            @for ($i = 0; $i < $fullStars; $i++)
                                <svg style="width:13px;height:13px;color:#F59E0B;flex-shrink:0;" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            @if ($halfStar)
                                <svg style="width:13px;height:13px;flex-shrink:0;" viewBox="0 0 20 20">
                                    <defs>
                                        <linearGradient id="pd-half-star">
                                            <stop offset="50%" stop-color="#F59E0B" />
                                            <stop offset="50%" stop-color="#e5e7eb" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#pd-half-star)"
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endif
                            @for ($i = 0; $i < $emptyStars; $i++)
                                <svg style="width:13px;height:13px;color:#e5e7eb;flex-shrink:0;" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            <span class="pd-rnum">{{ $avgRating }}</span>
                            <span class="pd-rct">({{ $totalReviews }} rating)</span>
                        </span>
                    @endif

                    @if ($store?->city)
                        <span>{{ $store->city }}</span>
                    @endif
                </div>

                <div class="pd-price" x-text="formattedPrice"></div>

                <div class="pd-divider"></div>

                {{-- Variants --}}
                @if ($hasVariants)
                    <div class="pd-v-label">
                        Pilih Varian:
                        <b x-show="selectedVariant" x-text="selectedVariant"></b>
                        <b x-show="!selectedVariant" style="color:#aaa; font-weight:400;">—</b>
                    </div>
                    <div class="pd-chips">
                        @foreach ($product->variants as $variant)
                            <div class="pd-chip {{ $variant->stok == 0 ? 'disabled' : '' }}"
                                @if ($variant->stok > 0) onclick="pdSelectVariant(this, '{{ $variant->nama_varian }}', {{ $product->harga + $variant->harga_tambahan }}, {{ $variant->stok }}, {{ $variant->variant_id }})"
                                 x-on:click="
                                     price = {{ $product->harga + $variant->harga_tambahan }};
                                     maxStock = {{ $variant->stok }};
                                     selectedVariant = '{{ $variant->nama_varian }}';
                                     variantSelected = true;
                                     if(qty > maxStock) qty = maxStock > 0 ? maxStock : 1;
                                     $el.closest('.pd-chips').querySelectorAll('.pd-chip').forEach(c => c.classList.remove('active'));
                                     $el.classList.add('active');
                                     document.getElementById('pdVariantInput').value = '{{ $variant->variant_id }}';
                                 " @endif>
                                {{ $variant->nama_varian }}
                                @if ($variant->stok == 0)
                                    <span style="font-size:10px; color:#f87171; margin-left:2px;">Habis</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Shipping Info --}}
                <div class="pd-ship-box">
                    <div class="pd-ship-title">Pengiriman</div>
                    <div class="pd-ship-row">
                        <svg width="20" height="20" fill="none" stroke="#2563EB" stroke-width="1.5"
                            viewBox="0 0 24 24" style="flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <div style="flex:1;">
                            <div class="pd-ship-svc">Reguler
                                @if ($estimasi)
                                    — estimasi {{ $estimasi }}
                                @endif
                            </div>
                            @if (!$estimasi)
                                <div class="pd-ship-est">Login untuk lihat estimasi pengiriman</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- INFO PENTING --}}
                <div class="pd-divider"></div>

                <div style="font-size:13px; font-weight:600; color:#111; margin-bottom:12px;">Info Penting</div>

                <div style="display:flex; border-bottom:2px solid #f0f0f0; margin-bottom:14px; gap:0;">
                    <button
                        style="padding:8px 16px; font-size:13px; font-weight:600; color:#2563EB;
                   border:none; background:none; cursor:default;
                   border-bottom:2px solid #2563EB; margin-bottom:-2px;
                   font-family:'Plus Jakarta Sans',sans-serif;">
                        Pengiriman
                    </button>
                </div>

                <div style="display:flex; align-items:flex-start; gap:10px; margin-bottom:14px;">
                    <svg width="16" height="16" fill="none" stroke="#888" stroke-width="1.5"
                        viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div>
                        <div
                            style="font-size:11px; color:#aaa; margin-bottom:2px; text-transform:uppercase; letter-spacing:.04em;">
                            Dikirim dari</div>
                        <div style="font-size:13px; font-weight:500; color:#222;">
                            {{ $store?->city ?? 'Lokasi tidak tersedia' }}
                        </div>
                    </div>
                </div>

                @if (count($shippingRows) > 0)
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <svg width="16" height="16" fill="none" stroke="#888" stroke-width="1.5"
                            viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <div style="flex:1;">
                            <div
                                style="font-size:11px; color:#aaa; margin-bottom:6px; text-transform:uppercase; letter-spacing:.04em;">
                                Ongkos kirim</div>
                            @foreach ($shippingRows as $row)
                                <div
                                    style="display:flex; justify-content:space-between; align-items:center;
                            padding:8px 0; border-bottom:1px solid #f5f5f5;">
                                    <div>
                                        <div style="font-size:13px; font-weight:500; color:#222;">{{ $row['label'] }}
                                        </div>
                                        <div style="font-size:11px; color:#aaa;">
                                            Est. {{ $row['est'] }} · Tiba {{ $row['tiba'] }}
                                        </div>
                                    </div>
                                    <span
                                        style="font-size:13px; font-weight:600; color:#2563EB; white-space:nowrap; margin-left:12px;">
                                        Rp{{ number_format($row['harga'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div
                        style="display:flex; align-items:center; gap:8px; padding:10px 12px;
                background:#f8faff; border-radius:8px; border:1px solid #dbeafe;">
                        <svg width="14" height="14" fill="none" stroke="#93c5fd" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span style="font-size:12px; color:#aaa;">Login untuk melihat estimasi ongkos kirim.</span>
                    </div>
                @endif
            </div>

            {{-- KANAN: PANEL AKSI --}}
            <div class="pd-col-action">
                <div class="pd-action-card">
                    <div class="pd-action-title">Atur jumlah dan catatan</div>

                    {{-- Selected variant preview --}}
                    <div class="pd-sel-var">
                        <img id="pdActionImg" class="pd-sel-img"
                            src="{{ asset('storage/' . ($primaryImage->gambar ?? '')) }}"
                            onerror="this.src='https://placehold.co/76x76/f5f5f5/aaa?text=?'" alt="">
                        <div style="flex:1;">
                            <div class="pd-sel-name">
                                @if ($hasVariants)
                                    <span x-show="variantSelected"
                                        x-text="selectedVariant + (selectedVariant ? '' : '')"></span>
                                    <span x-show="!variantSelected" style="color:#aaa;">Pilih varian dulu</span>
                                @else
                                    {{ $product->nama_produk }}
                                @endif
                            </div>
                            <div class="pd-sel-line"></div>
                        </div>
                        <span class="pd-sel-arrow">›</span>
                    </div>

                    {{-- Qty + Stok --}}
                    <div class="pd-qty-row">
                        <span class="pd-qty-lbl">Jumlah</span>
                        <div class="pd-qty-right">
                            <div class="pd-qty-ctrl">
                                <button type="button" class="pd-qty-btn" @click="if(qty > 1) qty--">−</button>
                                <div class="pd-qty-num" x-text="qty"></div>
                                <button type="button" class="pd-qty-btn"
                                    @click="if(qty < maxStock) qty++">+</button>
                            </div>
                            <span class="pd-stok">
                                Stok: <b x-text="'Sisa ' + maxStock"></b>
                            </span>
                        </div>
                    </div>

                    {{-- Subtotal --}}
                    <div class="pd-subtotal-row">
                        <span class="pd-sub-lbl">Subtotal</span>
                        <span class="pd-sub-val" x-text="formattedSubtotal"></span>
                    </div>

                    {{-- CTA Buttons --}}
                    <form id="purchaseForm" action="{{ route('checkout.review') }}" method="GET">
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="variant_id" id="pdVariantInput" value="">
                        <input type="hidden" name="quantity" x-bind:value="qty">

                        <template x-if="variantSelected && maxStock > 0">
                            <div>
                                <button type="button" onclick="pdSubmitCart()" class="pd-btn-cart">+
                                    Keranjang</button>
                                <button type="submit" class="pd-btn-buy">Beli Langsung</button>
                            </div>
                        </template>
                        <template x-if="!variantSelected || maxStock == 0">
                            <div>
                                <div class="pd-btn-disabled">
                                    {{ $hasVariants ? 'Pilih varian terlebih dahulu' : 'Stok habis' }}
                                </div>
                            </div>
                        </template>
                    </form>

                    <p
                        style="text-align:center; font-size:11px; color:#aaa; margin-top:10px; display:flex; align-items:center; justify-content:center; gap:5px;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Transaksi aman &amp; terverifikasi
                    </p>
                </div>

                <div class="pd-links-card">
                    @auth
                        <button onclick="pdToggleWishlist(this)" data-product-id="{{ $product->product_id }}"
                            data-wishlisted="{{ $isWishlisted ? '1' : '0' }}" class="pd-alink"
                            style="background:none; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif;">
                            <svg fill="{{ $isWishlisted ? '#ef4444' : 'none' }}"
                                stroke="{{ $isWishlisted ? '#ef4444' : 'currentColor' }}" viewBox="0 0 24 24"
                                style="width:15px;height:15px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span id="wishlist-label">{{ $isWishlisted ? 'Wishlisted' : 'Wishlist' }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="pd-alink">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="width:15px;height:15px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Wishlist
                        </a>
                    @endauth
                </div>
            </div>

        </div>

        {{-- REVIEW SECTION --}}
        <div class="pd-review-card">
            @include('products._review_section', [
                'product' => $product,
                'reviews' => $reviews,
                'eligibleOrders' => $eligibleOrders,
            ])
        </div>

    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
            });
        </script>
    @endif

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('snap_token'))
                window.snap.pay("{{ session('snap_token') }}", {
                    onSuccess: () => window.location.href = "{{ route('orders.history') }}",
                    onPending: () => window.location.href = "{{ route('orders.history') }}",
                    onError: () => handleCancel(),
                    onClose: () => handleCancel()
                });

                function handleCancel() {
                    fetch("{{ route('orders.cancel', session('order_id')) }}", {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.json())
                        .then(() => {
                            Swal.fire({
                                icon: 'info',
                                title: 'Pesanan Dibatalkan',
                                text: 'Stok sudah dikembalikan.',
                                confirmButtonText: 'Oke'
                            }).then(() => window.location.reload());
                        });
                }
            @endif
        });

        function pdSetMain(thumb, src) {
            document.getElementById('pdMainImg').src = src;
            document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        /**
         * pdSelectVariant — dipanggil saat chip varian diklik
         * Juga mengupdate foto di panel kanan (pdActionImg) & foto utama galeri
         */
        function pdSelectVariant(el, name, price, stock, variantId) {
            // Update chips active state
            document.querySelectorAll('.pd-chip:not(.disabled)').forEach(c => c.classList.remove('active'));
            el.classList.add('active');

            // Update foto panel kanan
            const actionImg = document.getElementById('pdActionImg');
            if (actionImg && pdVariantImages[variantId]) {
                actionImg.style.opacity = '0';
                setTimeout(() => {
                    actionImg.src = pdVariantImages[variantId];
                    actionImg.style.opacity = '1';
                }, 120);
            }

            // Update foto utama galeri juga
            const mainImg = document.getElementById('pdMainImg');
            if (mainImg && pdVariantImages[variantId]) {
                mainImg.style.opacity = '0';
                setTimeout(() => {
                    mainImg.src = pdVariantImages[variantId];
                    mainImg.style.opacity = '1';
                    mainImg.style.transition = 'opacity .2s ease';
                }, 120);
            }
        }

        function pdSubmitCart() {
            const f = document.getElementById('purchaseForm');
            document.getElementById('cartProductId').value = f.querySelector('[name=product_id]').value;
            document.getElementById('cartVariantId').value = f.querySelector('[name=variant_id]').value;
            document.getElementById('cartQty').value = f.querySelector('[name=quantity]').value;
            document.getElementById('cartForm').submit();
        }

        async function pdToggleWishlist(btn) {
            const productId = btn.dataset.productId;
            const svg = btn.querySelector('svg');
            const label = btn.querySelector('span');

            try {
                const res = await fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    }),
                });

                const data = await res.json();

                if (data.wishlisted) {
                    svg.setAttribute('fill', '#ef4444');
                    svg.setAttribute('stroke', '#ef4444');
                    label.textContent = 'Wishlisted';
                    btn.dataset.wishlisted = '1';
                } else {
                    svg.setAttribute('fill', 'none');
                    svg.setAttribute('stroke', 'currentColor');
                    label.textContent = 'Wishlist';
                    btn.dataset.wishlisted = '0';
                }

                const badge = document.getElementById('wishlist-nav-count');
                if (badge) badge.textContent = data.count;

            } catch (e) {
                console.error(e);
            }
        }
    </script>

    <form id="cartForm" action="{{ route('cart.add') }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="product_id" id="cartProductId">
        <input type="hidden" name="variant_id" id="cartVariantId">
        <input type="hidden" name="quantity" id="cartQty">
    </form>

</x-app-layout>
