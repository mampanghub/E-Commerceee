<x-app-layout>
    <style>
        .wl-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .wl-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .wl-title {
            font-size: 18px;
            font-weight: 700;
            color: #111;
        }

        .wl-count {
            font-size: 13px;
            color: #888;
            margin-top: 2px;
        }

        .wl-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
        }

        .wl-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            transition: box-shadow .15s;
        }

        .wl-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .wl-card-img {
            aspect-ratio: 1;
            overflow: hidden;
            background: #f9f9f9;
        }

        .wl-card-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
            transition: transform .3s;
        }

        .wl-card:hover .wl-card-img img {
            transform: scale(1.04);
        }

        .wl-card-body {
            padding: 10px 12px 12px;
        }

        .wl-card-name {
            font-size: 12.5px;
            color: #222;
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .wl-card-price {
            font-size: 14px;
            font-weight: 700;
            color: #111;
        }

        .wl-card-store {
            font-size: 11px;
            color: #aaa;
            margin-top: 2px;
        }

        .wl-remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .15s;
            backdrop-filter: blur(4px);
            z-index: 20;
        }

        .wl-remove-btn:hover {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .wl-remove-btn svg {
            width: 14px;
            height: 14px;
            stroke: #ef4444;
            fill: none;
            stroke-width: 2;
        }

        .wl-empty {
            text-align: center;
            padding: 80px 20px;
            color: #aaa;
        }

        .wl-empty svg {
            width: 64px;
            height: 64px;
            stroke: #ddd;
            fill: none;
            stroke-width: 1.2;
            margin-bottom: 16px;
        }

        .wl-empty-title {
            font-size: 16px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }

        .wl-empty-sub {
            font-size: 13px;
            margin-bottom: 20px;
        }

        .wl-btn-shop {
            display: inline-block;
            padding: 10px 24px;
            background: #03AC0E;
            color: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background .15s;
        }

        .wl-btn-shop:hover {
            background: #029B0C;
        }
    </style>

    <div class="wl-page">
        <div class="wl-header">
            <div>
                <div class="wl-title">Wishlist Saya</div>
                <div class="wl-count">{{ $wishlists->count() }} produk disimpan</div>
            </div>
            @if ($wishlists->count() > 0)
                <a href="{{ route('dashboard') }}"
                    style="font-size:13px;color:#03AC0E;text-decoration:none;font-weight:500;">
                    + Tambah produk
                </a>
            @endif
        </div>

        @if ($wishlists->isEmpty())
            <div class="wl-empty">
                <svg viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <div class="wl-empty-title">Wishlist masih kosong</div>
                <div class="wl-empty-sub">Simpan produk favorit kamu di sini</div>
                <a href="{{ route('dashboard') }}" class="wl-btn-shop">Mulai Belanja</a>
            </div>
        @else
            <div class="wl-grid">
                @foreach ($wishlists as $wl)
                    @php
                        $prod = $wl->product;
                        $img = $prod->primaryImage ?? $prod->images->first();
                    @endphp

                    <div class="wl-card" style="position: relative;"> {{-- Pastikan relative --}}

                        {{-- Tombol Hapus (Taruh di luar tag <a> agar tidak bentrok) --}}
                        <form method="POST" action="{{ route('wishlist.remove', $wl->wishlist_id) }}"
                            style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="wl-remove-btn" title="Hapus dari wishlist">
                                <svg viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>

                        {{-- Link Produk --}}
                        <a href="{{ route('products.show', $prod->product_id) }}"
                            style="text-decoration:none; color:inherit; display:block; z-index: 10; position: relative;">
                            <div class="wl-card-img">
                                <img src="{{ asset('storage/' . ($img->gambar ?? '')) }}"
                                    onerror="this.src='https://placehold.co/400x400/f5f5f5/aaa?text=No+Image'"
                                    alt="{{ $prod->nama_produk }}">
                            </div>
                            <div class="wl-card-body">
                                <div class="wl-card-name">{{ $prod->nama_produk }}</div>
                                <div class="wl-card-price">Rp{{ number_format($prod->harga, 0, ',', '.') }}</div>
                                @if ($prod->store)
                                    <div class="wl-card-store">{{ $prod->store->nama_toko }}</div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
