<x-app-layout>

    <x-slot name="header"></x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --mp-blue-50: #eff6ff;
            --mp-blue-100: #dbeafe;
            --mp-blue-500: #3b82f6;
            --mp-blue-600: #1d4ed8;
            --mp-blue-700: #1e40af;
            --mp-blue-800: #1e3a8a;
            --mp-blue-900: #0f2460;
            --mp-orange: #f97316;
            --mp-gray-50: #f8fafc;
            --mp-gray-100: #f1f5f9;
            --mp-gray-200: #e2e8f0;
            --mp-gray-500: #64748b;
            --mp-gray-700: #334155;
        }

        .mp-page * {
            box-sizing: border-box;
        }

        .mp-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--mp-gray-50);
            color: var(--mp-gray-700);
        }

        /* HERO */
        .hero-banner {
            background: linear-gradient(135deg, var(--mp-blue-800) 0%, var(--mp-blue-600) 55%, #3b82f6 100%);
            border-radius: 16px;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 32px;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, .07);
            border-radius: 50%;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: 80px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-block;
            background: var(--mp-orange);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 10px;
            letter-spacing: -.5px;
        }

        .hero-title span {
            color: #fde68a;
        }

        .hero-sub {
            font-size: 14px;
            color: #bfdbfe;
            margin: 0 auto 22px;
            max-width: 380px;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: var(--mp-blue-700);
            font-weight: 700;
            font-size: 14px;
            padding: 11px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: transform .18s, box-shadow .18s;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .2);
        }

        .hero-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .25);
        }

        .hero-cta svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: var(--mp-blue-700);
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .hero-deco {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 12px;
            z-index: 1;
        }

        .hero-deco-item {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, .12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, .2);
        }

        @media (max-width:1024px) {
            .hero-deco {
                display: none;
            }
        }

        /* SECTION HEADER */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--mp-gray-700);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title .dot {
            width: 4px;
            height: 20px;
            background: var(--mp-blue-600);
            border-radius: 2px;
            display: inline-block;
        }

        .section-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--mp-blue-600);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .section-link:hover {
            color: var(--mp-blue-800);
        }

        .section-link svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* CATEGORY CARDS */
        .category-section {
            margin-bottom: 28px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 12px;
        }

        .cat-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px 8px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: 1.5px solid var(--mp-gray-200);
            transition: all .2s;
        }

        .cat-card:hover {
            border-color: var(--mp-blue-500);
            box-shadow: 0 4px 16px rgba(59, 130, 246, .15);
            transform: translateY(-2px);
        }

        .cat-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--mp-blue-50);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .cat-name {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--mp-gray-700);
            text-align: center;
            line-height: 1.3;
        }

        /* PRODUCT GRID */
        .products-section {
            margin-bottom: 28px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        .product-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            border: 1.5px solid var(--mp-gray-200);
            text-decoration: none;
            transition: all .2s;
            position: relative;
            display: block;
        }

        .product-card:hover {
            box-shadow: 0 6px 24px rgba(30, 64, 175, .13);
            transform: translateY(-3px);
            border-color: #bfdbfe;
        }

        .product-img-wrap {
            width: 100%;
            aspect-ratio: 1/1;
            overflow: hidden;
            background: var(--mp-gray-100);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s;
        }

        .product-card:hover .product-img-wrap img {
            transform: scale(1.05);
        }

        .product-img-placeholder {
            font-size: 56px;
        }

        .product-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            z-index: 1;
        }

        .product-badge.new {
            background: var(--mp-blue-600);
        }

        .product-info {
            padding: 10px 12px 12px;
        }

        .product-name {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--mp-gray-700);
            margin-bottom: 5px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 15px;
            font-weight: 800;
            color: var(--mp-blue-700);
        }

        .product-loc {
            font-size: 10.5px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 3px;
            margin-top: 3px;
        }

        .product-stock {
            font-size: 10.5px;
            font-weight: 600;
            margin-top: 4px;
        }

        .product-stock.ok {
            color: #16a34a;
        }

        .product-stock.low {
            color: #f97316;
        }

        .product-stock.out {
            color: #ef4444;
        }

        .empty-products {
            grid-column: 1/-1;
            text-align: center;
            padding: 48px 0;
            color: var(--mp-gray-500);
        }

        .empty-products .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .empty-products p {
            font-size: 14px;
        }

        /* FOOTER */
        .mp-footer {
            background: var(--mp-blue-900);
            color: #94a3b8;
            padding: 48px 0 0;
            margin-top: 40px;
        }

        .mp-footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
            padding-bottom: 40px;
        }

        .footer-brand .footer-logo {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: -.5px;
        }

        .footer-brand .footer-logo span {
            color: #93c5fd;
        }

        .footer-brand p {
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
        }

        .social-btn {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, .08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            text-decoration: none;
            transition: background .18s;
        }

        .social-btn:hover {
            background: var(--mp-blue-600);
        }

        .footer-col h4 {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 14px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 8px;
        }

        .footer-col ul li a {
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            transition: color .18s;
        }

        .footer-col ul li a:hover {
            color: #bfdbfe;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding: 18px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        .footer-payment {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .payment-badge {
            background: rgba(255, 255, 255, .1);
            color: #cbd5e1;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        /* RESPONSIVE */
        @media (max-width:1024px) {
            .category-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }
        }

        @media (max-width:768px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .category-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>

    <div class="mp-page">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- HERO --}}
            <div class="hero-banner">
                <div class="hero-content">
                    <div class="hero-badge">⚡ Koleksi Terbaru</div>
                    <h1 class="hero-title">Pakaian Terbaik,<br>Harga <span>Terjangkau</span></h1>
                    <p class="hero-sub">Temukan ribuan pilihan pakaian pria, wanita, dan anak-anak.</p>
                    <a href="{{ url('/login') }}" class="hero-cta">
                        Mulai Belanja
                        <svg viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                </div>
                <div class="hero-deco">
                    <div class="hero-deco-item">👕</div>
                    <div class="hero-deco-item" style="margin-top:24px;">👗</div>
                    <div class="hero-deco-item">🧥</div>
                </div>
            </div>

            {{-- KATEGORI --}}
            @if ($categories->count() > 0)
                <section class="category-section">
                    <div class="section-header">
                        <div class="section-title"><span class="dot"></span> Kategori Pilihan</div>
                    </div>
                    <div class="category-grid">
                        @foreach ($categories as $cat)
                            <a href="{{ url('/products?category=' . $cat->category_id) }}" class="cat-card">
                                <div class="cat-icon">{{ $cat->icon ?? '🏷️' }}</div>
                                <span class="cat-name">{{ $cat->nama_kategori ?? $cat->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- PRODUK TERBARU --}}
            <section class="products-section">
                <div class="section-header">
                    <div class="section-title"><span class="dot"></span> Produk Terbaru</div>
                    <a href="{{ url('/login') }}" class="section-link">
                        Lihat Semua <svg viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                </div>

                <div class="product-grid">
                    @forelse ($products as $product)
                        <a href="{{ url('/login') }}" class="product-card">
                            <div class="product-img-wrap">
                                @if ($product->primaryImage && $product->primaryImage->gambar)
                                    <img src="{{ Storage::url($product->primaryImage->gambar) }}"
                                        alt="{{ $product->nama_produk }}" loading="lazy"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <div class="product-img-placeholder" style="display:none;">👕</div>
                                @else
                                    <div class="product-img-placeholder">👕</div>
                                @endif

                                @if ($product->total_stok <= 0)
                                    <span class="product-badge" style="background:#6b7280;">Habis</span>
                                @elseif ($product->created_at->diffInDays(now()) <= 7)
                                    <span class="product-badge new">Baru</span>
                                @endif
                            </div>

                            <div class="product-info">
                                <div class="product-name">{{ $product->nama_produk }}</div>
                                <div>
                                    <span class="product-price">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                                </div>
                                @php
    $avg  = round($product->reviews_avg_bintang ?? 0, 1);
    $full = floor($avg);
    $half = ($avg - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
@endphp
<div style="display:flex;align-items:center;gap:4px;margin-top:5px;">
    @for($i = 0; $i < $full; $i++)
        <svg style="width:12px;height:12px;color:#f59e0b;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
    @endfor
    @if($half)
        <svg style="width:12px;height:12px;flex-shrink:0;" viewBox="0 0 20 20">
            <defs><linearGradient id="half-{{ $product->product_id }}"><stop offset="50%" stop-color="#f59e0b"/><stop offset="50%" stop-color="#e2e8f0"/></linearGradient></defs>
            <path fill="url(#half-{{ $product->product_id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    @endif
    @for($i = 0; $i < $empty; $i++)
        <svg style="width:12px;height:12px;color:#e2e8f0;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
    @endfor
    @if($product->reviews_count > 0)
        <span style="font-size:10px;font-weight:700;color:#64748b;">{{ $avg }}</span>
        <span style="font-size:10px;color:#94a3b8;">({{ $product->reviews_count }})</span>
    @else
        <span style="font-size:10px;color:#cbd5e1;">Belum ada ulasan</span>
    @endif
    @if(($product->terjual ?? 0) > 0)
        <span style="font-size:10px;color:#94a3b8;">· {{ $product->terjual }}+ terjual</span>
    @endif
</div>
                                @if ($product->store)
                                    <div class="product-loc">📍 {{ $product->store->nama_toko ?? $product->store->name }}</div>
                                @endif
                                @php $stok = $product->total_stok; @endphp
                                @if ($stok <= 0)
                                    <div class="product-stock out">Stok habis</div>
                                @elseif ($stok <= 5)
                                    <div class="product-stock low">Sisa {{ $stok }} item</div>
                                @else
                                    <div class="product-stock ok">Stok tersedia</div>
                                @endif
                            </div>
                        </a>

                    @empty
                        <div class="empty-products">
                            <div class="empty-icon">🛍️</div>
                            <p>Belum ada produk yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>

    </div>

</x-app-layout>