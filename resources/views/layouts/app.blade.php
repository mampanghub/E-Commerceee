<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ===== FOOTER ===== */
        .mp-footer {
            background: #0f2460;
            color: #94a3b8;
            padding: 48px 0 0;
            margin-top: 40px;
        }

        .mp-footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 280px 140px 230px 140px 140px;
            gap: 80px;
            padding-bottom: 40px;
        }

        /* Pastikan semua kolom tidak melar karena konten */
        .footer-grid>div {
            min-width: 0;
            overflow: hidden;
        }

        .footer-brand .footer-logo {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: -.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .footer-brand .footer-logo span {
            color: #93c5fd;
        }

        .footer-brand p {
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 6px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #94a3b8;
        }

        .footer-brand p a {
            color: #93c5fd;
            text-decoration: none;
            transition: color .15s;
        }

        .footer-brand p a:hover {
            color: #fff;
        }

        .footer-contact-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 10px;
            margin-top: 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .footer-social-btn {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, .08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background .18s;
            border: 1px solid rgba(255, 255, 255, .1);
            flex-shrink: 0;
        }

        .footer-social-btn:hover {
            background: #1d4ed8;
            border-color: transparent;
        }

        .footer-social-btn svg {
            width: 17px;
            height: 17px;
            fill: #cbd5e1;
            transition: fill .18s;
        }

        .footer-social-btn:hover svg {
            fill: #fff;
        }

        .footer-col h4 {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            white-space: nowrap;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col ul li {
            margin-bottom: 8px;
        }

        .footer-col ul li a {
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            transition: color .18s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .footer-col ul li a:hover {
            color: #bfdbfe;
        }

        /* Khusus link Hubungi Kami yang punya flex + icon, boleh wrap */
        .footer-col ul li a.contact-link {
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
            word-break: break-all;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding: 18px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #64748b;
        }

        .footer-payment {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .footer-payment-badge {
            background: rgba(255, 255, 255, .1);
            color: #cbd5e1;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        @media (max-width:1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }
        }

        @media (max-width:640px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .footer-brand {
                grid-column: 1 / -1;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .footer-bottom>div:first-child {
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- Footer hanya untuk pembeli dan guest, bukan admin --}}
    @if (!auth()->check() || auth()->user()->role !== 'admin')
        <footer class="mp-footer">
            <div class="mp-footer-inner">
                <div class="footer-grid">

                    {{-- BRAND & SOSIAL --}}
                    <div class="footer-brand" style="display:flex;flex-direction:column;">
                        <div>
                            <div class="footer-logo">Mampang<span>Pedia</span></div>
                            <p>Platform belanja fashion online terpercaya. Temukan ribuan pilihan pakaian berkualitas
                                dari penjual terbaik di seluruh Indonesia.</p>
                        </div>
                        <div class="footer-socials">
                            {{-- Facebook --}}
                            <a href="https://www.facebook.com/?locale=id_ID" target="_blank" class="footer-social-btn"
                                title="Facebook">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            {{-- Instagram --}}
                            <a href="https://www.instagram.com/" target="_blank" class="footer-social-btn"
                                title="Instagram">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                            {{-- TikTok --}}
                            <a href="https://www.tiktok.com/id-ID/" target="_blank" class="footer-social-btn"
                                title="TikTok">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z" />
                                </svg>
                            </a>
                            {{-- WhatsApp --}}
                            <a href="https://wa.me/6289612189321" target="_blank" class="footer-social-btn"
                                title="WhatsApp">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- MENU (dinamis per role) --}}
                    <div class="footer-col">
                        @auth
                            @if (auth()->user()->role === 'admin')
                                <h4>Menu Admin</h4>
                                <ul>
                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li><a href="{{ route('products.index') }}">Produk</a></li>
                                    <li><a href="{{ route('orders.index') }}">Pesanan</a></li>
                                    <li><a href="{{ route('categories.index') }}">Kategori</a></li>
                                    <li><a href="{{ route('admin.users.index') }}">Users</a></li>
                                    <li><a href="{{ route('admin.laporan') }}">Laporan</a></li>
                                </ul>
                            @elseif(auth()->user()->role === 'pembeli')
                                <h4>Menu Saya</h4>
                                <ul>
                                    <li><a href="{{ route('dashboard') }}">Beranda</a></li>
                                    <li><a href="{{ route('cart.index') }}">Keranjang</a></li>
                                    <li><a href="{{ route('orders.history') }}">Pesanan Saya</a></li>
                                    <li><a href="{{ route('profile.edit') }}">Akun Saya</a></li>
                                    <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                                </ul>
                            @endif
                        @else
                            <h4>Menu</h4>
                            <ul>
                                <li><a href="{{ route('home') }}">Beranda</a></li>
                                <li><a href="{{ route('login') }}">Masuk</a></li>
                                <li><a href="{{ route('register') }}">Daftar</a></li>
                            </ul>
                        @endauth
                    </div>

                    {{-- HUBUNGI KAMI --}}
                    <div class="footer-col">
                        <h4>Hubungi Kami</h4>
                        <ul>
                            <li>
                                <a href="#" class="contact-link">
                                    <svg style="width:13px;height:13px;flex-shrink:0;margin-top:2px;fill:none;stroke:#93c5fd;stroke-width:2;"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Mampang, Pancoran Mas, Depok, Jawa Barat</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://wa.me/6289612189321" class="contact-link" style="align-items:center;">
                                    <svg style="width:13px;height:13px;flex-shrink:0;fill:none;stroke:#93c5fd;stroke-width:2;"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>0896-1218-9321</span>
                                </a>
                            </li>
                            <li>
                                <a href="mailto:wisnurmdhn10@gmail.com" class="contact-link"
                                    style="align-items:center;">
                                    <svg style="width:13px;height:13px;flex-shrink:0;fill:none;stroke:#93c5fd;stroke-width:2;"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>wisnurmdhn10@gmail.com</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- JAM OPERASIONAL --}}
                    <div class="footer-col">
                        <h4>Jam Operasional</h4>
                        <p
                            style="font-size:13px;color:#94a3b8;font-family:'Plus Jakarta Sans',sans-serif;line-height:1.7;">
                            Senin – Sabtu<br>08.00 – 22.00 WIB
                        </p>
                        <p
                            style="font-size:13px;color:#94a3b8;font-family:'Plus Jakarta Sans',sans-serif;line-height:1.7;margin-top:12px;">
                            Minggu<br>08.00 – 20.00 WIB
                        </p>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="footer-col">
                        <h4>Kategori</h4>
                        <ul>
                            <li>
                                <a href="{{ route('dashboard') }}">Semua</a>
                            </li>
                            @foreach (\App\Models\Category::all() as $cat)
                                <li>
                                    <a href="{{ route('dashboard', ['category' => $cat->category_id]) }}">
                                        {{ $cat->nama_kategori }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <div class="footer-bottom">
                    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                        <span>© {{ date('Y') }} MampangPedia. Semua hak dilindungi.</span>
                    </div>

                    <div class="footer-payment">
                        <span style="font-size:11px;color:#64748b;margin-right:4px;">Metode Bayar:</span>
                        <span class="footer-payment-badge">BCA</span>
                        <span class="footer-payment-badge">Mandiri</span>
                        <span class="footer-payment-badge">BRI</span>
                        <span class="footer-payment-badge">BNI</span>
                        <span class="footer-payment-badge">BSI</span>
                    </div>
                </div>
            </div>
        </footer>
    @endif

    {{-- Loading Screen --}}
    <div id="mp-loading"
        style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(30,64,175,0.55); backdrop-filter:blur(8px); align-items:center; justify-content:center; flex-direction:column; gap:20px; overflow:hidden;">
        <div style="display:flex;flex-direction:column;align-items:center;gap:16px;z-index:1;">
            <div style="animation:mp-cart-walk 0.6s ease-in-out infinite alternate;">
                <svg width="64" height="64" fill="none" viewBox="0 0 24 24"
                    style="filter:drop-shadow(0 4px 12px rgba(147,197,253,0.4));">
                    <path stroke="#93c5fd" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    <rect x="10" y="6" width="4" height="3" rx="1" fill="#93c5fd"
                        style="animation:mp-item-bounce 0.6s ease-in-out infinite alternate;" />
                </svg>
            </div>
            <div
                style="font-family:'Plus Jakarta Sans',sans-serif;font-size:28px;font-weight:800;color:#fff;letter-spacing:-1px;">
                Mampang<span style="color:#93c5fd;">Pedia</span>
            </div>
            <div
                style="font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;color:rgba(147,197,253,0.7);letter-spacing:2px;text-transform:uppercase;">
                Sedang memuat...
            </div>
        </div>
        <div
            style="width:160px;height:3px;background:rgba(255,255,255,0.15);border-radius:99px;overflow:hidden;z-index:1;">
            <div
                style="height:100%;background:#93c5fd;border-radius:99px;animation:mp-progress 1.8s ease-in-out infinite;">
            </div>
        </div>
    </div>

    <style>
        @keyframes mp-cart-walk {
            0% {
                transform: translateX(-6px) rotate(-5deg)
            }

            100% {
                transform: translateX(6px) rotate(5deg)
            }
        }

        @keyframes mp-item-bounce {
            0% {
                transform: translateY(0)
            }

            100% {
                transform: translateY(-3px)
            }
        }

        @keyframes mp-progress {
            0% {
                width: 0%;
                margin-left: 0%
            }

            50% {
                width: 70%;
                margin-left: 15%
            }

            100% {
                width: 0%;
                margin-left: 100%
            }
        }
    </style>

    <script>
        (function() {
            const loader = document.getElementById('mp-loading');
            document.querySelectorAll('a').forEach(a => {
                if (a.href && !a.href.startsWith('#') && !a.href.startsWith('mailto') && a.target !==
                    '_blank') {
                    a.addEventListener('click', () => loader.style.display = 'flex');
                }
            });
            window.addEventListener('pageshow', () => loader.style.display = 'none');
        })();
    </script>
</body>

</html>
