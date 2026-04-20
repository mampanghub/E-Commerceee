<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .mp-nav * {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ===== TOPBAR ===== */
    .mp-topbar {
        background-color: #1e3a8a;
        color: #cbd5e1;
        font-size: 11.5px;
        padding: 6px 0;
    }

    .mp-topbar-inner {
        max-width: 1920px;
        margin: 0 auto;
        padding: 0 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mp-topbar a {
        color: #cbd5e1;
        text-decoration: none;
        transition: color .15s;
    }

    .mp-topbar a:hover {
        color: #fff;
    }

    .mp-topbar-right {
        display: flex;
        gap: 20px;
    }

    /* ===== NAVBAR ===== */
    .mp-nav {
        background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
        box-shadow: 0 2px 16px rgba(30, 64, 175, 0.4);
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .mp-nav-inner {
        max-width: 1920px;
        margin: 0 auto;
        padding: 0 40px;
        display: flex;
        align-items: center;
        height: 64px;
        gap: 24px;
    }

    /* Logo */
    .mp-logo {
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        text-decoration: none;
        letter-spacing: -0.5px;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
        shrink: 0;
    }

    .mp-logo-icon {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .2s;
    }

    .mp-logo:hover .mp-logo-icon {
        background: rgba(255, 255, 255, 0.25);
    }

    .mp-logo-icon svg {
        width: 20px;
        height: 20px;
        color: #fff;
    }

    .mp-logo span {
        color: #93c5fd;
    }

    /* Nav Links (admin/pembeli) */
    .mp-nav-links {
        display: flex;
        align-items: center;
        gap: 2px;
        flex-shrink: 0;
    }

    .mp-nav-link {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        white-space: nowrap;
        transition: all .18s;
        border: 1.5px solid transparent;
    }

    .mp-nav-link:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .mp-nav-link.active {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .mp-nav-link svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
    }

    /* Search Bar */
    .mp-search-wrap {
        flex: 1;
        display: flex;
        justify-content: center;
        padding: 0 16px;
    }

    .mp-search-form {
        position: relative;
        width: 100%;
        max-width: 600px;
    }

    .mp-search-form svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: #94a3b8;
        pointer-events: none;
    }

    .mp-search-form input {
        width: 100%;
        padding: 9px 16px 9px 40px;
        border-radius: 8px;
        border: 2px solid transparent;
        outline: none;
        font-size: 13.5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        transition: all .2s;
        backdrop-filter: blur(4px);
    }

    .mp-search-form input::placeholder {
        color: rgba(255, 255, 255, 0.55);
    }

    .mp-search-form input:focus {
        background: #fff;
        color: #334155;
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.3);
    }

    .mp-search-form input:focus::placeholder {
        color: #94a3b8;
    }

    .mp-search-form input:focus~svg,
    .mp-search-form:focus-within svg {
        color: #3b82f6;
    }

    /* Right Actions */
    .mp-nav-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* Cart Icon */
    .mp-cart-btn {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        padding: 6px 10px;
        border-radius: 8px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 10.5px;
        font-weight: 500;
        transition: all .18s;
    }

    .mp-cart-btn:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .mp-cart-btn svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .mp-cart-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        border-radius: 9999px;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #1d4ed8;
        animation: bounce 1s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(-10%)
        }

        50% {
            transform: translateY(0)
        }
    }

    /* Auth Buttons (guest) */
    .mp-btn-masuk {
        font-size: 13px;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        padding: 7px 14px;
        border-radius: 8px;
        white-space: nowrap;
        transition: all .18s;
        border: 1.5px solid rgba(255, 255, 255, 0.3);
    }

    .mp-btn-masuk:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .mp-btn-daftar {
        font-size: 13px;
        font-weight: 700;
        color: #1e40af;
        text-decoration: none;
        padding: 7px 18px;
        border-radius: 8px;
        white-space: nowrap;
        background: #fff;
        transition: all .18s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .mp-btn-daftar:hover {
        background: #eff6ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Avatar Dropdown Trigger */
    .mp-avatar-btn {
        width: 38px;
        height: 38px;
        border-radius: 9999px;
        border: 2.5px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        cursor: pointer;
        transition: border-color .2s;
        background: none;
        padding: 0;
    }

    .mp-avatar-btn:hover {
        border-color: rgba(255, 255, 255, 0.8);
    }

    .mp-avatar-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Divider */
    .mp-nav-divider {
        width: 1px;
        height: 28px;
        background: rgba(255, 255, 255, 0.2);
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .mp-topbar {
            display: none;
        }

        .mp-nav-inner {
            padding: 0 14px;
            height: 56px;
            gap: 0;
            justify-content: space-between;
        }

        .mp-nav-links {
            display: none;
        }

        .mp-search-wrap {
            display: none;
        }

        .mp-cart-btn {
            display: none;
        }

        .mp-nav-divider {
            display: none;
        }

        .mp-logo {
            font-size: 18px;
            gap: 6px;
            margin-right: 0 !important;
        }

        .mp-logo-icon {
            padding: 5px;
        }

        .mp-logo-icon svg {
            width: 18px;
            height: 18px;
        }

        .mp-nav-right {
            gap: 0;
        }

        .mp-avatar-btn {
            width: 34px;
            height: 34px;
        }

        /* ===== FIX DROPDOWN MOBILE ===== */
        /* Override Jetstream dropdown supaya tidak overflow layar */
        .mp-nav [x-data]>div[style*="position"],
        .mp-nav [x-data]>div {
            position: static !important;
        }
    }

    /* ===== MOBILE BOTTOM NAV ===== */
    .mp-bottom-nav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-top: 1px solid #e2e8f0;
        z-index: 100;
        padding: 6px 0 env(safe-area-inset-bottom, 6px);
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
    }

    .mp-bottom-nav-inner {
        display: flex;
        justify-content: space-around;
        align-items: center;
        max-width: 480px;
        margin: 0 auto;
    }

    .mp-bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        padding: 4px 12px;
        text-decoration: none;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border-radius: 10px;
        transition: all .18s;
        position: relative;
        min-width: 56px;
    }

    .mp-bottom-nav-item.active {
        color: #1d4ed8;
    }

    .mp-bottom-nav-item svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .mp-bottom-nav-badge {
        position: absolute;
        top: 2px;
        right: 10px;
        background: #ef4444;
        color: #fff;
        font-size: 9px;
        font-weight: 800;
        border-radius: 9999px;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #fff;
    }

    /* Beri padding bawah pada body supaya konten tidak tertutup bottom nav */
    body.has-bottom-nav {
        padding-bottom: 68px;
    }

    @media (max-width: 768px) {
        .mp-bottom-nav {
            display: block;
        }
    }

    .mp-dropdown-content {
        width: 300px;
    }

    @media (max-width: 768px) {

        /* Dropdown muncul dari kanan, lebar penuh dikurangi margin */
        .mp-dropdown-content {
            width: min(300px, calc(100vw - 24px));
        }

        /* Pastikan dropdown container tidak overflow */
        .mp-nav-right {
            position: relative;
        }

        /* Fix x-dropdown positioning di mobile */
        .mp-nav .absolute {
            right: 0 !important;
            left: auto !important;
            max-width: calc(100vw - 24px);
        }
    }
</style>

{{-- ===== TOP BAR ===== --}}
<div class="mp-topbar">
    <div class="mp-topbar-inner">
        <div class="mp-topbar-right">

        </div>
    </div>
</div>

{{-- ===== MAIN NAVBAR ===== --}}
<nav class="mp-nav" x-data="{ open: false }">
    <div class="mp-nav-inner">

        {{-- LOGO --}}
        <a href="{{ route('home') }}" class="mp-logo" style="margin-right: 8px;">
            <div class="mp-logo-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            Mampang<span>Pedia</span>
        </a>

        {{-- NAV LINKS (berdasarkan role) --}}
        @auth
            <div class="mp-nav-links">
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}"
                        class="mp-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="mp-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Produk
                    </a>
                    <a href="{{ route('orders.index') }}"
                        class="mp-nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}"
                        style="position:relative;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Pesanan
                        @if (($pendingOrderCount ?? 0) > 0)
                            <span
                                style="
                                    position:absolute;
                                    top:-4px;
                                    right:-8px;
                                    background:#ef4444;
                                    color:#fff;
                                    font-size:10px;
                                    font-weight:800;
                                    border-radius:9999px;
                                    min-width:18px;
                                    height:18px;
                                    padding:0 4px;
                                    display:inline-flex;
                                    align-items:center;
                                    justify-content:center;
                                    line-height:1;
                                    border:2px solid #1d4ed8;
                                ">{{ $pendingOrderCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="mp-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Kategori
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="mp-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Users
                    </a>
                    <a href="{{ route('admin.laporan') }}"
                        class="mp-nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Laporan
                    </a>
                @elseif (auth()->user()->role === 'pembeli')
                    <a href="{{ route('dashboard') }}"
                        class="mp-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('orders.history') }}"
                        class="mp-nav-link {{ request()->routeIs('orders.history') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Pesanan Saya
                    </a>
                @endif
            </div>
        @endauth

        {{-- SEARCH BAR --}}
        @if (Request::is('/') || (Auth::check() && Auth::user()->role === 'pembeli' && Request::is('dashboard')))
            <div class="mp-search-wrap">
                <div class="mp-search-form" id="search-wrapper" style="position:relative;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <form action="{{ auth()->check() ? route('dashboard') : route('home') }}" method="GET"
                        style="display:contents;">
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                            placeholder="Mau cari barang apa hari ini?" autocomplete="off">
                    </form>
                    <div id="search-dropdown"
                        style="
                display:none;
                position:absolute;
                top:calc(100% + 8px);
                left:0;
                right:0;
                background:#fff;
                border:1px solid #e2e8f0;
                border-radius:12px;
                box-shadow:0 8px 32px rgba(30,64,175,0.15);
                z-index:9999;
                overflow:hidden;
            ">
                    </div>
                </div>
            </div>
        @else
            <div style="flex:1;"></div>
        @endif

        <script>
            (function() {
                const input = document.getElementById('search-input');
                const dropdown = document.getElementById('search-dropdown');
                if (!input || !dropdown) return;

                let timer;

                input.addEventListener('input', function() {
                    clearTimeout(timer);
                    const q = this.value.trim();

                    if (q.length === 0) {
                        dropdown.style.display = 'none';
                        return;
                    }

                    timer = setTimeout(async () => {
                        try {
                            const res = await fetch(
                                `{{ route('search.suggestions') }}?q=${encodeURIComponent(q)}`);
                            const data = await res.json();

                            if (data.length === 0) {
                                dropdown.innerHTML = `
                        <div style="padding:16px;text-align:center;font-size:13px;color:#94a3b8;font-family:'Plus Jakarta Sans',sans-serif;">
                            Produk tidak ditemukan
                        </div>`;
                                dropdown.style.display = 'block';
                                return;
                            }

                            dropdown.innerHTML = data.map(p => `
                    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}?search=${encodeURIComponent(p.nama_produk)}"style="
                        display:flex;
                        align-items:center;
                        gap:12px;
                        padding:10px 14px;
                        text-decoration:none;
                        color:#1e293b;
                        font-size:13px;
                        font-family:'Plus Jakarta Sans',sans-serif;
                        border-bottom:1px solid #f1f5f9;
                        transition:background .15s;
                        background:#fff;
                    " onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#fff'">
                        <img src="{{ asset('storage') }}/${p.foto_produk}" style="width:40px;height:40px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid #e2e8f0;" onerror="this.style.display='none'"/>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;color:#1e3a8a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${p.nama_produk}</div>
                            <div style="font-size:12px;color:#3b82f6;font-weight:700;margin-top:2px;">Rp ${Number(p.harga).toLocaleString('id-ID')}</div>
                        </div>
                        <svg style="width:14px;height:14px;flex-shrink:0;stroke:#cbd5e1;fill:none;stroke-width:2;" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                `).join('');

                            dropdown.style.display = 'block';
                        } catch (e) {
                            dropdown.style.display = 'none';
                        }
                    }, 250);
                });

                document.addEventListener('click', function(e) {
                    if (!document.getElementById('search-wrapper').contains(e.target)) {
                        dropdown.style.display = 'none';
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') dropdown.style.display = 'none';
                });
            })();
        </script>

        {{-- RIGHT SECTION --}}
        <div class="mp-nav-right">

            {{-- CART — pembeli only --}}
            @if (Auth::check() && Auth::user()->role === 'pembeli')
                <a href="{{ route('cart.index') }}" class="mp-cart-btn" title="Keranjang">
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Keranjang</span>
                    @if (($cartCount ?? 0) > 0)
                        <span class="mp-cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="mp-nav-divider"></div>
            @endif

            {{-- PROFILE DROPDOWN (logged in) --}}
            @auth
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="mp-avatar-btn">
                            <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                                alt="{{ Auth::user()->name }}">
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="mp-dropdown-content" style="font-family:'Plus Jakarta Sans',sans-serif;">

                            {{-- Header dropdown --}}
                            <div
                                style="padding:16px; display:flex; align-items:center; gap:12px; background:#eff6ff; border-radius:8px 8px 0 0; border-bottom:1px solid #dbeafe;">
                                <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                                    style="width:44px;height:44px;border-radius:9999px;flex-shrink:0;">
                                <div>
                                    <div style="font-size:14px;font-weight:700;color:#1e3a8a;">{{ Auth::user()->name }}
                                    </div>
                                    <div style="font-size:11px;color:#3b82f6;font-weight:600;text-transform:capitalize;">
                                        {{ Auth::user()->role }}
                                    </div>
                                </div>
                            </div>

                            @if (auth()->user()->role === 'pembeli')
                                {{-- Saldo --}}
                                <div
                                    style="padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div
                                            style="width:28px;height:28px;background:#dcfce7;border-radius:9999px;display:flex;align-items:center;justify-content:center;">
                                            <svg style="width:14px;height:14px;stroke:#16a34a;fill:none;stroke-width:2;"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <span style="font-size:13px;color:#475569;">Saldo</span>
                                    </div>
                                    <span style="font-size:13px;font-weight:700;color:#1e3a8a;">
                                        Rp{{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>

                                {{-- Menu pembeli --}}
                                <div style="padding:6px 0;">
                                    <a href="{{ route('profile.edit') }}"
                                        style="display:flex;align-items:center;gap:10px;padding:10px 16px;font-size:13px;color:#475569;text-decoration:none;transition:background .15s;"
                                        onmouseover="this.style.background='#f1f5f9'"
                                        onmouseout="this.style.background='none'">
                                        <svg style="width:15px;height:15px;stroke:#94a3b8;fill:none;stroke-width:2;flex-shrink:0;"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Pengaturan Akun
                                    </a>
                                    <a href="{{ route('orders.history') }}"
                                        style="display:flex;align-items:center;gap:10px;padding:10px 16px;font-size:13px;color:#475569;text-decoration:none;transition:background .15s;"
                                        onmouseover="this.style.background='#f1f5f9'"
                                        onmouseout="this.style.background='none'">
                                        <svg style="width:15px;height:15px;stroke:#94a3b8;fill:none;stroke-width:2;flex-shrink:0;"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        Pembelian Saya
                                    </a>
                                    <a href="{{ route('wishlist.index') }}"
                                        style="display:flex;align-items:center;gap:10px;padding:10px 16px;font-size:13px;color:#475569;text-decoration:none;transition:background .15s;"
                                        onmouseover="this.style.background='#f1f5f9'"
                                        onmouseout="this.style.background='none'">
                                        <svg style="width:15px;height:15px;stroke:#94a3b8;fill:none;stroke-width:2;flex-shrink:0;"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        Wishlist Saya
                                    </a>
                                </div>
                            @elseif (auth()->user()->role === 'admin')
                                <div style="padding:6px 0;">
                                    <a href="{{ route('profile.edit') }}"
                                        style="display:flex;align-items:center;gap:10px;padding:10px 16px;font-size:13px;color:#475569;text-decoration:none;"
                                        onmouseover="this.style.background='#f1f5f9'"
                                        onmouseout="this.style.background='none'">
                                        <svg style="width:15px;height:15px;stroke:#94a3b8;fill:none;stroke-width:2;flex-shrink:0;"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil Saya
                                    </a>

                                    <a href="{{ route('admin.shipping-settings.index') }}"
                                        style="display:flex;align-items:center;gap:10px;padding:10px 16px;font-size:13px;color:#475569;text-decoration:none;"
                                        onmouseover="this.style.background='#f1f5f9'"
                                        onmouseout="this.style.background='none'">
                                        <svg style="width:15px;height:15px;stroke:#94a3b8;fill:none;stroke-width:2;flex-shrink:0;"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Pengaturan
                                    </a>
                                </div>
                            @endif

                            {{-- Logout --}}
                            <div style="border-top:1px solid #e2e8f0;padding:6px 0 4px;">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 16px;font-size:13px;color:#ef4444;background:none;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;text-align:left;"
                                        onmouseover="this.style.background='#fef2f2'"
                                        onmouseout="this.style.background='none'">
                                        <svg style="width:15px;height:15px;stroke:#ef4444;fill:none;stroke-width:2;flex-shrink:0;"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>
            @else
                {{-- GUEST BUTTONS --}}
                <a href="{{ route('login') }}" class="mp-btn-masuk">Masuk</a>
                <a href="{{ route('register') }}" class="mp-btn-daftar">Daftar</a>
            @endauth
        </div>
    </div>
    {{-- ===== MOBILE BOTTOM NAVIGATION ===== --}}
    <div class="mp-bottom-nav" id="mp-bottom-nav">
        <div class="mp-bottom-nav-inner">
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Produk
                    </a>
                    <a href="{{ route('orders.index') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        @if (($pendingOrderCount ?? 0) > 0)
                            <span
                                class="mp-bottom-nav-badge">{{ $pendingOrderCount > 99 ? '99+' : $pendingOrderCount }}</span>
                        @endif
                        Pesanan
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Kategori
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Users
                    </a>
                @elseif (auth()->user()->role === 'pembeli')
                    <a href="{{ route('dashboard') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Beranda
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if (($cartCount ?? 0) > 0)
                            <span class="mp-bottom-nav-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                        @endif
                        Keranjang
                    </a>
                    <a href="{{ route('wishlist.index') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Wishlist
                    </a>
                    <a href="{{ route('orders.history') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('orders.history') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Pesanan
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="mp-bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Akun
                    </a>
                @endif
            @else
                <a href="{{ route('home') }}" class="mp-bottom-nav-item active">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('login') }}" class="mp-bottom-nav-item">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="mp-bottom-nav-item">
                    <svg viewBox="0 0 24 24">
                        <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Daftar
                </a>
            @endauth
        </div>
    </div>

    <script>
        if (window.innerWidth <= 768) {
            document.body.classList.add('has-bottom-nav');
        }
    </script>
</nav>
