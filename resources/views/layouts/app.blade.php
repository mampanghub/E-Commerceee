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
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .footer-brand .footer-logo span {
            color: #93c5fd;
        }

        .footer-brand p {
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
        }

        .footer-social-btn {
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

        .footer-social-btn:hover {
            background: #1d4ed8;
        }

        .footer-col h4 {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
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
            font-family: 'Plus Jakarta Sans', sans-serif;
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
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 12px;
                text-align: center;
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
                    <div class="footer-brand">
                        <div class="footer-logo">Mampang<span>Pedia</span></div>
                        <p>Platform belanja fashion online terpercaya. Temukan ribuan pilihan pakaian berkualitas dari
                            penjual terbaik di seluruh Indonesia.</p>
                        <div class="footer-socials">
                            <a href="#" class="footer-social-btn" title="Instagram">📸</a>
                            <a href="#" class="footer-social-btn" title="TikTok">🎵</a>
                            <a href="#" class="footer-social-btn" title="Facebook">📘</a>
                            <a href="#" class="footer-social-btn" title="Twitter/X">🐦</a>
                        </div>
                    </div>
                    <div class="footer-col">
                        <h4>Layanan</h4>
                        <ul>
                            <li><a href="#">Pusat Bantuan</a></li>
                            <li><a href="#">Lacak Pesanan</a></li>
                            <li><a href="#">Pengembalian Barang</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Jual di Sini</h4>
                        <ul>
                            <li><a href="#">Daftar sebagai Penjual</a></li>
                            <li><a href="#">Panduan Berjualan</a></li>
                            <li><a href="#">Promosi Toko</a></li>
                            <li><a href="#">Program Afiliasi</a></li>
                            <li><a href="#">Iklan MampangPedia</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Tentang Kami</h4>
                        <ul>
                            <li><a href="#">Tentang MampangPedia</a></li>
                            <li><a href="#">Blog & Tips Fashion</a></li>
                            <li><a href="#">Karir</a></li>
                            <li><a href="#">Berita & Press</a></li>
                            <li><a href="#">Hubungi Kami</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Pembayaran</h4>
                        <ul>
                            <li><a href="#">Transfer Bank</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <span>© {{ date('Y') }} MampangPedia.</span>
                    <div class="footer-payment">
                        <span style="font-size:11px;color:#94a3b8;margin-right:4px;">Metode Bayar:</span>
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

            {{-- Cart icon animasi --}}
            <div style="animation:mp-cart-walk 0.6s ease-in-out infinite alternate;">
                <svg width="64" height="64" fill="none" viewBox="0 0 24 24"
                    style="filter:drop-shadow(0 4px 12px rgba(147,197,253,0.4));">
                    <path stroke="#93c5fd" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    {{-- item bouncing di cart --}}
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
        (function () {
            const loader = document.getElementById('mp-loading');
            document.querySelectorAll('a').forEach(a => {
                if (a.href && !a.href.startsWith('#') && !a.href.startsWith('mailto') && a.target !== '_blank') {
                    a.addEventListener('click', () => loader.style.display = 'flex');
                }
            });
            window.addEventListener('pageshow', () => loader.style.display = 'none');
        })();
    </script>
</body>

</html>