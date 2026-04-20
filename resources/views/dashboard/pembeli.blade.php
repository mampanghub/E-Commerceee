<x-app-layout>

    <x-slot name="header"></x-slot>

    @if (auth()->user()->role === 'pembeli')

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

            .product-card-disabled {
                pointer-events: none;
                cursor: not-allowed;
            }

            .product-card-disabled .product-img-wrap {
                opacity: 0.5;
                filter: grayscale(60%);
            }

            .mp-page {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--mp-gray-50);
                color: var(--mp-gray-700);
            }

            /* ===== MOBILE SEARCH BAR ===== */
            .mp-mobile-search {
                display: none;
                background: linear-gradient(135deg, var(--mp-blue-800) 0%, var(--mp-blue-600) 100%);
                padding: 10px 16px 14px;
            }

            .mp-mobile-search-inner {
                position: relative;
            }

            .mp-mobile-search-inner svg {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                width: 15px;
                height: 15px;
                stroke: #94a3b8;
                fill: none;
                stroke-width: 2.5;
                pointer-events: none;
            }

            .mp-mobile-search-inner input {
                width: 100%;
                padding: 10px 16px 10px 38px;
                border-radius: 10px;
                border: none;
                font-size: 14px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                outline: none;
                background: #fff;
                color: #334155;
            }

            .mp-mobile-search-inner input::placeholder {
                color: #94a3b8;
            }

            /* ===== HERO ===== */
            .hero-section {
                margin-bottom: 16px;
            }

            .hero-banner {
                background: linear-gradient(135deg, var(--mp-blue-800) 0%, var(--mp-blue-600) 55%, #3b82f6 100%);
                border-radius: 16px;
                padding: 36px 40px;
                position: relative;
                overflow: hidden;
                min-height: 220px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
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

            /* ===== SECTION HEADER ===== */
            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 14px;
            }

            .section-title {
                font-size: 16px;
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

            /* ===== CATEGORY ===== */
            .category-section {
                margin-bottom: 16px;
            }

            .category-grid {
                display: grid;
                grid-template-columns: repeat(8, 1fr);
                gap: 12px;
            }

            .cat-card {
                background: #fff;
                border-radius: 12px;
                padding: 16px 8px 12px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
                text-decoration: none;
                border: 1.5px solid var(--mp-gray-200);
                transition: all .2s;
            }

            .cat-card:hover,
            .cat-card.active-cat {
                border-color: var(--mp-blue-500);
                box-shadow: 0 4px 16px rgba(59, 130, 246, .15);
            }

            .cat-icon {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: var(--mp-blue-50);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
            }

            .cat-name {
                font-size: 11px;
                font-weight: 600;
                color: var(--mp-gray-700);
                text-align: center;
                line-height: 1.3;
            }

            /* ===== FILTER ===== */
            .filter-select {
                appearance: none;
                background: #fff;
                padding: 8px 32px 8px 12px;
                border-radius: 8px;
                border: 1.5px solid var(--mp-gray-200);
                font-size: 13px;
                font-weight: 600;
                color: var(--mp-gray-700);
                cursor: pointer;
                outline: none;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 10px center;
            }

            /* ===== PRODUCT GRID ===== */
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

            .btn-more {
                width: auto;
                height: auto;
                background: none;
                border: none;
                padding: 0;
                display: inline-flex;
                align-items: center;
                cursor: pointer;
                z-index: 10;
                transition: color .15s;
                font-size: 20px;
                font-weight: 900;
                color: #64748b;
                line-height: 1;
                flex-shrink: 0;
            }

            .btn-more:hover {
                background: none;
                color: #334155;
            }

            .more-dropdown {
                position: absolute;
                bottom: 36px;
                right: 0;
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.10);
                z-index: 100;
                min-width: 170px;
                overflow: hidden;
                display: none;
            }

            .more-dropdown.show {
                display: block;
                animation: dropIn .15s ease;
            }

            @keyframes dropIn {
                from {
                    opacity: 0;
                    transform: translateY(-6px) scale(0.97);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .more-dropdown-item {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 14px;
                font-size: 13px;
                font-weight: 600;
                color: #334155;
                cursor: pointer;
                transition: background .1s;
                border: none;
                background: none;
                width: 100%;
                text-align: left;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .more-dropdown-item:hover {
                background: #f1f5f9;
            }

            /* ===== TOAST NOTIF ===== */
            .wl-toast {
                position: fixed;
                bottom: 28px;
                left: 50%;
                transform: translateX(-50%) translateY(80px);
                background: #1e293b;
                color: #fff;
                padding: 13px 22px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 600;
                font-family: 'Plus Jakarta Sans', sans-serif;
                display: flex;
                align-items: center;
                gap: 8px;
                z-index: 9999;
                box-shadow: 0 8px 28px rgba(0, 0, 0, 0.22);
                transition: transform .35s cubic-bezier(.34, 1.56, .64, 1), opacity .3s;
                opacity: 0;
                pointer-events: none;
                white-space: nowrap;
            }

            .wl-toast.show {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 1024px) {
                .category-grid {
                    grid-template-columns: repeat(4, 1fr);
                }

                .product-grid {
                    grid-template-columns: repeat(4, 1fr);
                }

                .hero-deco {
                    display: none;
                }
            }

            @media (max-width: 768px) {
                .mp-mobile-search {
                    display: block;
                }

                .mp-page>.mp-container {
                    padding-left: 12px !important;
                    padding-right: 12px !important;
                    padding-top: 16px !important;
                }

                .hero-banner {
                    border-radius: 12px;
                    padding: 28px 20px;
                    min-height: auto;
                }

                .hero-title {
                    font-size: 22px;
                }

                .hero-sub {
                    font-size: 12px;
                    margin-bottom: 16px;
                }

                .hero-badge {
                    font-size: 10px;
                }

                .hero-cta {
                    font-size: 13px;
                    padding: 9px 18px;
                }

                .hero-deco {
                    display: none;
                }

                .hero-section {
                    margin-bottom: 16px;
                }

                .category-grid {
                    display: flex;
                    flex-wrap: nowrap;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-width: none;
                    gap: 8px;
                    padding-bottom: 4px;
                }

                .category-grid::-webkit-scrollbar {
                    display: none;
                }

                .cat-card {
                    min-width: 68px;
                    flex-shrink: 0;
                    padding: 12px 6px 10px;
                    border-radius: 10px;
                }

                .cat-icon {
                    width: 40px;
                    height: 40px;
                    font-size: 18px;
                }

                .cat-name {
                    font-size: 10px;
                }

                .category-section {
                    margin-bottom: 16px;
                }

                .product-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 10px;
                }

                .product-name {
                    font-size: 12px;
                }

                .product-price {
                    font-size: 13px;
                }

                .product-info {
                    padding: 8px 10px 10px;
                }

                .section-header {
                    flex-wrap: wrap;
                    gap: 8px;
                }

                .section-title {
                    font-size: 14px;
                }
            }

            /* ===== CAROUSEL ===== */
            .cs-wrap {
                border-radius: 16px;
                overflow: hidden;
                position: relative;
                user-select: none;
                margin-bottom: 16px;
            }

            .cs-track {
                display: flex;
                transition: transform .55s cubic-bezier(.77, 0, .18, 1);
            }

            .cs-slide {
                min-width: 100%;
                padding: 32px 40px;
                box-sizing: border-box;
                display: flex;
                align-items: center;
                justify-content: space-between;
                min-height: 200px;
                position: relative;
                overflow: hidden;
            }

            .s0 {
                background: linear-gradient(135deg, #1a3fcf 0%, #2563EB 50%, #3b7ff5 100%);
            }

            .s1 {
                background: linear-gradient(135deg, #7c3aed 0%, #9333ea 50%, #a855f7 100%);
            }

            .s2 {
                background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%);
            }

            .cs-bg-c {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, .06);
                pointer-events: none;
            }

            .cs-left {
                position: relative;
                z-index: 2;
                max-width: 500px;
            }

            .cs-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .08em;
                padding: 5px 14px;
                border-radius: 999px;
                margin-bottom: 14px;
                color: #fff;
            }

            .b0 {
                background: #f97316;
            }

            .b1 {
                background: #db2777;
            }

            .b2 {
                background: #0891b2;
            }

            .cs-dot {
                width: 6px;
                height: 6px;
                background: rgba(255, 255, 255, .8);
                border-radius: 50%;
                animation: csDotBlink 1.2s ease-in-out infinite;
            }

            @keyframes csDotBlink {

                0%,
                100% {
                    opacity: 1
                }

                50% {
                    opacity: .3
                }
            }

            .cs-title {
                font-size: 28px;
                font-weight: 800;
                color: #fff;
                margin: 0 0 8px;
                line-height: 1.2;
            }

            .cs-title span {
                color: #fbbf24;
            }

            .s1 .cs-title span {
                color: #fbcfe8;
            }

            .s2 .cs-title span {
                color: #a7f3d0;
            }

            .cs-sub {
                font-size: 13px;
                color: rgba(255, 255, 255, .78);
                margin: 0 0 20px;
                line-height: 1.5;
            }

            .cs-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #fff;
                font-size: 13px;
                font-weight: 700;
                padding: 10px 22px;
                border-radius: 10px;
                border: none;
                cursor: pointer;
                text-decoration: none;
                transition: transform .15s, box-shadow .15s;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .s0 .cs-btn {
                color: #1d4ed8;
            }

            .s1 .cs-btn {
                color: #7c3aed;
            }

            .s2 .cs-btn {
                color: #0f766e;
            }

            .cs-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, .18);
            }

            .cs-arr {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 20px;
                height: 20px;
                background: #eff6ff;
                border-radius: 6px;
                font-size: 13px;
            }

            .cs-right {
                position: absolute;
                bottom: 100px;
                /* taruh di bawah, di atas dots carousel */
                left: 52%;
                transform: translateX(-50%);
                display: flex;
                flex-direction: row;
                /* horizontal seperti semula */
                align-items: center;
                gap: 12px;
                z-index: 3;
            }

            .cs-icon {
                width: 70px;
                height: 70px;
                background: rgba(255, 255, 255, .12);
                border: 1px solid rgba(255, 255, 255, .2);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 30px;
            }

            .cs-icon.big {
                width: 84px;
                height: 84px;
                font-size: 36px;
                background: rgba(255, 255, 255, .18);
            }

            .fl1 {
                animation: csFloat 3s ease-in-out infinite;
            }

            .fl2 {
                animation: csFloat 2.6s ease-in-out infinite;
                animation-delay: .5s;
            }

            .fl3 {
                animation: csFloat 3.4s ease-in-out infinite;
                animation-delay: .2s;
            }

            @keyframes csFloat {

                0%,
                100% {
                    transform: translateY(0)
                }

                50% {
                    transform: translateY(-8px)
                }
            }

            .cs-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: rgba(255, 255, 255, .2);
                border: 1px solid rgba(255, 255, 255, .3);
                color: #fff;
                font-size: 20px;
                cursor: pointer;
                z-index: 10;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background .15s, opacity .25s;
                line-height: 1;
                opacity: 0;
            }

            .cs-wrap:hover .cs-nav {
                opacity: 1;
            }

            .cs-nav:hover {
                background: rgba(255, 255, 255, .35);
            }

            .cs-prev {
                left: 14px;
            }

            .cs-next {
                right: 14px;
            }

            .cs-dots {
                position: absolute;
                bottom: 14px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 7px;
                z-index: 10;
            }

            .cs-pip {
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: rgba(255, 255, 255, .4);
                cursor: pointer;
                transition: all .3s;
            }

            .cs-pip.active {
                width: 24px;
                background: #fff;
            }

            .cs-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: rgba(255, 255, 255, .5);
                z-index: 10;
                border-radius: 0 2px 2px 0;
            }

            @media (max-width: 768px) {
                .cs-right {
                    display: none;
                }

                .cs-slide {
                    padding: 32px 20px;
                    min-height: 180px;
                }

                .cs-title {
                    font-size: 22px;
                }

                .cs-center-illus {
                    display: none;
                }
            }

            /* ===== CENTER ILLUSTRATION ===== */
            .cs-center-illus {
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 45%;
                z-index: 1;
                pointer-events: none;
                display: flex;
                align-items: flex-end;
                justify-content: center;
                overflow: hidden;
            }

            .cs-center-illus svg {
                width: 100%;
                height: 100%;
                max-height: 280px;
            }
        </style>

        {{-- MOBILE SEARCH BAR --}}
        <div class="mp-mobile-search">
            <form action="{{ route('dashboard') }}" method="GET">
                <div class="mp-mobile-search-inner">
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk...">
                </div>
            </form>
        </div>

        <div class="mp-page">
            <div class="mp-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-0">

                {{-- HERO --}}
                <section class="hero-section">
                    <div class="cs-wrap" id="cs">
                        <div class="cs-track" id="csTrack">

                            <!-- SLIDE 1 — Koleksi Terbaru -->
                            <div class="cs-slide s0">
                                <div class="cs-bg-c" style="width:280px;height:280px;right:-60px;top:-100px;"></div>
                                <div class="cs-bg-c" style="width:140px;height:140px;left:42%;bottom:-50px;"></div>

                                <!-- ILUSTRASI SLIDE 1: Orang pegang baju colorful -->
                                <div class="cs-center-illus">
                                    <svg viewBox="0 0 340 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- ground shadow -->
                                        <ellipse cx="170" cy="232" rx="70" ry="8"
                                            fill="rgba(0,0,0,.2)" />
                                        <!-- ===== BIG SHIRT being held out ===== -->
                                        <!-- shirt body orange -->
                                        <path
                                            d="M90 148 Q112 136 136 144 L170 200 L204 144 Q228 136 250 148 L240 178 Q220 166 206 182 L170 220 L134 182 Q120 166 100 178Z"
                                            fill="#f97316" />
                                        <!-- shirt collar -->
                                        <path d="M136 144 Q170 158 204 144" fill="none" stroke="#ea580c"
                                            stroke-width="2" />
                                        <!-- shirt sleeve left -->
                                        <path d="M90 148 L100 178" stroke="#fb923c" stroke-width="2" fill="none" />
                                        <!-- shirt sleeve right -->
                                        <path d="M250 148 L240 178" stroke="#fb923c" stroke-width="2" fill="none" />
                                        <!-- shirt button placket -->
                                        <line x1="170" y1="156" x2="170" y2="202"
                                            stroke="#ea580c" stroke-width="1.5" stroke-dasharray="4,4" />
                                        <!-- shirt pocket -->
                                        <rect x="180" y="164" width="16" height="14" rx="2"
                                            fill="#fb923c" />
                                        <!-- shirt star logo -->
                                        <path
                                            d="M170 170 L172 176 L178 176 L173 180 L175 186 L170 182 L165 186 L167 180 L162 176 L168 176Z"
                                            fill="#fff" opacity="0.85" />
                                        <!-- price tag on shirt -->
                                        <rect x="232" y="124" width="36" height="24" rx="4"
                                            fill="#fde68a" />
                                        <line x1="232" y1="136" x2="226" y2="136"
                                            stroke="#fbbf24" stroke-width="2" />
                                        <circle cx="224" cy="136" r="3" fill="#f59e0b" />
                                        <text x="250" y="140" font-size="10" font-weight="800" fill="#92400e"
                                            text-anchor="middle">SALE</text>

                                        <!-- ===== PERSON ===== -->
                                        <!-- left arm reaching out -->
                                        <path d="M132 106 Q106 118 90 148" stroke="#0891b2" stroke-width="15"
                                            stroke-linecap="round" />
                                        <!-- right arm reaching out -->
                                        <path d="M208 106 Q234 118 250 148" stroke="#0891b2" stroke-width="15"
                                            stroke-linecap="round" />
                                        <!-- body / torso -->
                                        <ellipse cx="170" cy="108" rx="30" ry="36"
                                            fill="#0891b2" />
                                        <!-- shirt collar on person -->
                                        <path d="M148 88 Q170 98 192 88" fill="none" stroke="#0e7490"
                                            stroke-width="2" />
                                        <!-- shirt stripe detail -->
                                        <path d="M148 100 Q170 108 192 100" fill="none" stroke="#0e7490"
                                            stroke-width="1.5" opacity="0.6" />
                                        <!-- legs -->
                                        <rect x="153" y="138" width="14" height="44" rx="7"
                                            fill="#1e40af" />
                                        <rect x="173" y="138" width="14" height="44" rx="7"
                                            fill="#1e40af" />
                                        <!-- jeans crease -->
                                        <path d="M160 150 L160 170" stroke="#1e3a8a" stroke-width="1.5"
                                            opacity="0.5" />
                                        <path d="M180 150 L180 170" stroke="#1e3a8a" stroke-width="1.5"
                                            opacity="0.5" />
                                        <!-- shoes left -->
                                        <ellipse cx="158" cy="183" rx="13" ry="6"
                                            fill="#1e293b" />
                                        <ellipse cx="157" cy="181" rx="10" ry="4"
                                            fill="#334155" />
                                        <!-- shoes right -->
                                        <ellipse cx="182" cy="183" rx="13" ry="6"
                                            fill="#1e293b" />
                                        <ellipse cx="183" cy="181" rx="10" ry="4"
                                            fill="#334155" />
                                        <!-- HEAD -->
                                        <circle cx="170" cy="64" r="24" fill="#fcd9b6" />
                                        <!-- hair -->
                                        <path d="M147 56 Q148 38 170 36 Q192 38 193 56 Q188 44 170 42 Q152 44 147 56Z"
                                            fill="#92400e" />
                                        <path d="M147 56 Q143 64 147 72" fill="none" stroke="#92400e"
                                            stroke-width="9" stroke-linecap="round" />
                                        <path d="M193 56 Q197 64 193 72" fill="none" stroke="#92400e"
                                            stroke-width="9" stroke-linecap="round" />
                                        <!-- ears -->
                                        <ellipse cx="146" cy="66" rx="5" ry="7"
                                            fill="#f5c89a" />
                                        <ellipse cx="194" cy="66" rx="5" ry="7"
                                            fill="#f5c89a" />
                                        <!-- eyebrows raised happy -->
                                        <path d="M158 56 Q164 52 170 54" fill="none" stroke="#92400e"
                                            stroke-width="2" stroke-linecap="round" />
                                        <path d="M170 54 Q176 52 182 56" fill="none" stroke="#92400e"
                                            stroke-width="2" stroke-linecap="round" />
                                        <!-- eyes -->
                                        <circle cx="162" cy="63" r="4" fill="#1e293b" />
                                        <circle cx="178" cy="63" r="4" fill="#1e293b" />
                                        <circle cx="163.5" cy="61.5" r="1.5" fill="#fff" />
                                        <circle cx="179.5" cy="61.5" r="1.5" fill="#fff" />
                                        <!-- smile -->
                                        <path d="M160 72 Q170 80 180 72" fill="none" stroke="#c27d4a"
                                            stroke-width="2.5" stroke-linecap="round" />
                                        <!-- teeth -->
                                        <path d="M163 73 Q170 79 177 73" fill="#fff" />
                                        <!-- blush -->
                                        <ellipse cx="156" cy="70" rx="6" ry="3.5"
                                            fill="#fca5a5" opacity="0.55" />
                                        <ellipse cx="184" cy="70" rx="6" ry="3.5"
                                            fill="#fca5a5" opacity="0.55" />

                                        <!-- floating sparkles -->
                                        <path
                                            d="M96 68 L98 74 L104 74 L99 78 L101 84 L96 80 L91 84 L93 78 L88 74 L94 74Z"
                                            fill="#fde68a" opacity="0.8" />
                                        <circle cx="108" cy="56" r="4" fill="#fde68a" opacity="0.5" />
                                        <circle cx="260" cy="80" r="5" fill="rgba(255,255,255,.2)" />
                                        <circle cx="270" cy="96" r="3" fill="rgba(255,255,255,.15)" />
                                        <!-- confetti bits -->
                                        <rect x="264" y="60" width="8" height="8" rx="2"
                                            fill="#f9a8d4" opacity="0.7" transform="rotate(25 268 64)" />
                                        <rect x="82" y="100" width="7" height="7" rx="1"
                                            fill="#a5f3fc" opacity="0.6" transform="rotate(-15 85 103)" />
                                    </svg>
                                </div>

                                <div class="cs-left">
                                    <div class="cs-badge b0">
                                        <div class="cs-dot"></div>KOLEKSI TERBARU
                                    </div>
                                    <div class="cs-title">Halo, <span>{{ Auth::user()->name }}!</span></div>
                                    <div class="cs-sub">Temukan ribuan pilihan pakaian pria, wanita, dan anak-anak.
                                    </div>
                                    <a href="#produk" class="cs-btn">Mulai Belanja <span class="cs-arr">›</span></a>
                                </div>
                                <div class="cs-right">
                                    <div class="cs-icon fl1">👕</div>
                                    <div class="cs-icon big fl2">👗</div>
                                    <div class="cs-icon fl3">🧥</div>
                                </div>
                            </div>

                            <!-- SLIDE 2 — Flash Sale -->
                            <div class="cs-slide s1">
                                <div class="cs-bg-c" style="width:260px;height:260px;right:-50px;top:-90px;"></div>
                                <div class="cs-bg-c" style="width:160px;height:160px;left:38%;bottom:-60px;"></div>

                                <!-- ILUSTRASI SLIDE 2: Cewek excited loncat, shopping bags -->
                                <div class="cs-center-illus">
                                    <svg viewBox="0 0 340 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- ground shadow -->
                                        <ellipse cx="170" cy="232" rx="68" ry="7"
                                            fill="rgba(0,0,0,.18)" />

                                        <!-- ===== SHOPPING BAGS ===== -->
                                        <!-- bag left - pink big -->
                                        <rect x="62" y="148" width="58" height="72" rx="7"
                                            fill="#f9a8d4" />
                                        <path d="M70 148 Q70 128 91 128 Q112 128 112 148" fill="none"
                                            stroke="#ec4899" stroke-width="4" stroke-linecap="round" />
                                        <!-- bag left label -->
                                        <rect x="72" y="168" width="38" height="26" rx="4"
                                            fill="#ec4899" />
                                        <text x="91" y="183" font-size="12" font-weight="800" fill="#fff"
                                            text-anchor="middle">70%</text>
                                        <text x="91" y="196" font-size="9" font-weight="700" fill="#fce7f3"
                                            text-anchor="middle">OFF</text>
                                        <!-- bag left shine -->
                                        <path d="M74 152 Q80 148 88 150" fill="none" stroke="#fbcfe8"
                                            stroke-width="2" stroke-linecap="round" opacity="0.7" />

                                        <!-- bag right - yellow -->
                                        <rect x="218" y="156" width="52" height="64" rx="7"
                                            fill="#fde68a" />
                                        <path d="M225 156 Q225 138 244 138 Q263 138 263 156" fill="none"
                                            stroke="#f59e0b" stroke-width="4" stroke-linecap="round" />
                                        <!-- bag right label -->
                                        <rect x="228" y="172" width="34" height="22" rx="4"
                                            fill="#f59e0b" />
                                        <path
                                            d="M244 176 L246 182 L252 182 L247 186 L249 192 L244 188 L239 192 L241 186 L236 182 L242 182Z"
                                            fill="#fff" />
                                        <!-- bag right shine -->
                                        <path d="M229 160 Q235 156 242 158" fill="none" stroke="#fef3c7"
                                            stroke-width="2" stroke-linecap="round" opacity="0.7" />

                                        <!-- bag small teal - behind person -->
                                        <rect x="148" y="168" width="40" height="52" rx="5"
                                            fill="#5eead4" />
                                        <path d="M154 168 Q154 154 168 154 Q182 154 182 168" fill="none"
                                            stroke="#0d9488" stroke-width="3.5" stroke-linecap="round" />

                                        <!-- ===== PERSON - jumping excited ===== -->
                                        <!-- legs spread jump pose -->
                                        <path d="M162 162 Q148 180 140 200" stroke="#7c3aed" stroke-width="14"
                                            stroke-linecap="round" />
                                        <path d="M182 162 Q198 178 206 196" stroke="#7c3aed" stroke-width="14"
                                            stroke-linecap="round" />
                                        <!-- shoes -->
                                        <ellipse cx="137" cy="202" rx="13" ry="6"
                                            fill="#1e293b" />
                                        <ellipse cx="137" cy="200" rx="10" ry="4"
                                            fill="#374151" />
                                        <ellipse cx="208" cy="198" rx="13" ry="6"
                                            fill="#1e293b" />
                                        <ellipse cx="209" cy="196" rx="10" ry="4"
                                            fill="#374151" />
                                        <!-- body -->
                                        <ellipse cx="172" cy="130" rx="28" ry="35"
                                            fill="#a855f7" />
                                        <!-- shirt neckline -->
                                        <path d="M151 112 Q172 122 193 112" fill="none" stroke="#9333ea"
                                            stroke-width="2" />
                                        <!-- shirt star -->
                                        <path
                                            d="M172 122 L174 128 L180 128 L175 132 L177 138 L172 134 L167 138 L169 132 L164 128 L170 128Z"
                                            fill="#fde68a" opacity="0.9" />
                                        <!-- arms raised up -->
                                        <path d="M148 118 Q126 98 118 74" stroke="#a855f7" stroke-width="14"
                                            stroke-linecap="round" />
                                        <path d="M196 118 Q218 98 228 72" stroke="#a855f7" stroke-width="14"
                                            stroke-linecap="round" />
                                        <!-- hands with stars -->
                                        <circle cx="116" cy="70" r="12" fill="#fcd9b6" />
                                        <path
                                            d="M116 63 L118 69 L124 69 L119 73 L121 79 L116 75 L111 79 L113 73 L108 69 L114 69Z"
                                            fill="#fde68a" />
                                        <circle cx="230" cy="68" r="12" fill="#fcd9b6" />
                                        <path
                                            d="M230 61 L232 67 L238 67 L233 71 L235 77 L230 73 L225 77 L227 71 L222 67 L228 67Z"
                                            fill="#fde68a" />
                                        <!-- HEAD -->
                                        <circle cx="172" cy="92" r="23" fill="#fcd9b6" />
                                        <!-- hair - wavy fun -->
                                        <path d="M149 84 Q150 66 172 62 Q194 66 195 84" fill="#1e293b" />
                                        <path d="M149 84 Q145 93 149 100" fill="none" stroke="#1e293b"
                                            stroke-width="10" stroke-linecap="round" />
                                        <path d="M195 84 Q199 93 195 100" fill="none" stroke="#1e293b"
                                            stroke-width="10" stroke-linecap="round" />
                                        <!-- hair highlight -->
                                        <path d="M162 66 Q170 63 178 66" fill="none" stroke="#374151"
                                            stroke-width="3" stroke-linecap="round" opacity="0.5" />
                                        <!-- ears -->
                                        <ellipse cx="149" cy="93" rx="5" ry="7"
                                            fill="#f5c89a" />
                                        <ellipse cx="195" cy="93" rx="5" ry="7"
                                            fill="#f5c89a" />
                                        <!-- eyebrows excited arch -->
                                        <path d="M160 82 Q166 77 172 79" fill="none" stroke="#1e293b"
                                            stroke-width="2.5" stroke-linecap="round" />
                                        <path d="M172 79 Q178 77 184 82" fill="none" stroke="#1e293b"
                                            stroke-width="2.5" stroke-linecap="round" />
                                        <!-- eyes wide open happy -->
                                        <circle cx="163" cy="90" r="4.5" fill="#1e293b" />
                                        <circle cx="181" cy="90" r="4.5" fill="#1e293b" />
                                        <circle cx="164.5" cy="88.5" r="1.8" fill="#fff" />
                                        <circle cx="182.5" cy="88.5" r="1.8" fill="#fff" />
                                        <!-- big grin open mouth -->
                                        <path d="M160 100 Q172 112 184 100" fill="none" stroke="#c27d4a"
                                            stroke-width="2.5" stroke-linecap="round" />
                                        <path d="M163 101 Q172 110 181 101" fill="#fff" />
                                        <!-- blush big -->
                                        <ellipse cx="157" cy="96" rx="7" ry="4"
                                            fill="#fca5a5" opacity="0.6" />
                                        <ellipse cx="187" cy="96" rx="7" ry="4"
                                            fill="#fca5a5" opacity="0.6" />

                                        <!-- confetti scattered all around -->
                                        <rect x="100" y="56" width="9" height="9" rx="2"
                                            fill="#f97316" opacity="0.75" transform="rotate(30 104 60)" />
                                        <rect x="246" y="44" width="8" height="8" rx="2"
                                            fill="#06b6d4" opacity="0.7" transform="rotate(-20 250 48)" />
                                        <rect x="130" y="44" width="7" height="7" rx="1"
                                            fill="#a855f7" opacity="0.65" transform="rotate(45 133 47)" />
                                        <rect x="248" y="108" width="7" height="7" rx="2"
                                            fill="#fde68a" opacity="0.7" transform="rotate(15 251 111)" />
                                        <circle cx="108" cy="88" r="5" fill="#f9a8d4" opacity="0.7" />
                                        <circle cx="260" cy="128" r="4" fill="#a7f3d0" opacity="0.65" />
                                        <rect x="82" y="128" width="6" height="6" rx="1"
                                            fill="#fbbf24" opacity="0.6" transform="rotate(-10 85 131)" />
                                        <!-- sparkle lines -->
                                        <path d="M96 46 L96 54 M92 50 L100 50" stroke="#fde68a" stroke-width="2"
                                            stroke-linecap="round" opacity="0.7" />
                                        <path d="M258 56 L258 64 M254 60 L262 60" stroke="#a7f3d0" stroke-width="2"
                                            stroke-linecap="round" opacity="0.6" />
                                    </svg>
                                </div>

                                <div class="cs-left">
                                    <div class="cs-badge b1">
                                        <div class="cs-dot"></div>FLASH SALE
                                    </div>
                                    <div class="cs-title">Diskon hingga <span>70% Off!</span></div>
                                    <div class="cs-sub">Promo terbatas hanya hari ini. Jangan sampai kehabisan stok
                                        favoritmu.</div>
                                    <a href="#produk" class="cs-btn">Lihat Promo <span class="cs-arr">›</span></a>
                                </div>
                                <div class="cs-right">
                                    <div class="cs-icon fl3">🛍️</div>
                                    <div class="cs-icon big fl1">🏷️</div>
                                    <div class="cs-icon fl2">💳</div>
                                </div>
                            </div>

                            <!-- SLIDE 3 — Gratis Ongkir -->
                            <div class="cs-slide s2">
                                <div class="cs-bg-c" style="width:300px;height:300px;right:-70px;top:-110px;"></div>
                                <div class="cs-bg-c" style="width:120px;height:120px;left:44%;bottom:-40px;"></div>

                                <!-- ILUSTRASI SLIDE 3: Kurir dengan truk delivery -->
                                <div class="cs-center-illus">
                                    <svg viewBox="0 0 340 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- ground shadow -->
                                        <ellipse cx="185" cy="232" rx="120" ry="8"
                                            fill="rgba(0,0,0,.18)" />

                                        <!-- ===== DELIVERY TRUCK ===== -->
                                        <!-- truck cargo body white -->
                                        <rect x="60" y="140" width="150" height="74" rx="6"
                                            fill="#fff" />
                                        <!-- cargo top accent stripe -->
                                        <rect x="60" y="140" width="150" height="12" rx="3"
                                            fill="#e0f2fe" />
                                        <!-- cargo side graphic -->
                                        <rect x="72" y="162" width="126" height="36" rx="4"
                                            fill="#f0f9ff" />
                                        <text x="135" y="185" font-size="11" font-weight="800" fill="#0284c7"
                                            text-anchor="middle">GRATIS ONGKIR</text>
                                        <text x="135" y="197" font-size="8" font-weight="600" fill="#7dd3fc"
                                            text-anchor="middle">ke seluruh Indonesia</text>
                                        <!-- truck cabin teal -->
                                        <rect x="210" y="152" width="68" height="62" rx="6"
                                            fill="#0891b2" />
                                        <!-- cabin window -->
                                        <rect x="218" y="158" width="42" height="28" rx="5"
                                            fill="#bae6fd" />
                                        <!-- window glare -->
                                        <path d="M220 160 L234 160 L230 164 L220 164Z" fill="rgba(255,255,255,.45)" />
                                        <!-- cabin door line -->
                                        <line x1="239" y1="158" x2="239" y2="214"
                                            stroke="#0e7490" stroke-width="1.5" opacity="0.5" />
                                        <!-- door handle -->
                                        <rect x="232" y="188" width="14" height="5" rx="2.5"
                                            fill="#0e7490" />
                                        <!-- front bumper -->
                                        <rect x="276" y="200" width="10" height="8" rx="2"
                                            fill="#0e7490" />
                                        <!-- headlight -->
                                        <rect x="270" y="173" width="8" height="5" rx="2"
                                            fill="#fde68a" />
                                        <!-- truck chassis -->
                                        <rect x="60" y="208" width="220" height="8" rx="2"
                                            fill="#94a3b8" />
                                        <!-- wheels -->
                                        <circle cx="110" cy="216" r="16" fill="#1e293b" />
                                        <circle cx="110" cy="216" r="9" fill="#475569" />
                                        <circle cx="110" cy="216" r="4" fill="#94a3b8" />
                                        <!-- wheel bolts -->
                                        <circle cx="110" cy="207" r="1.5" fill="#cbd5e1" />
                                        <circle cx="110" cy="225" r="1.5" fill="#cbd5e1" />
                                        <circle cx="101" cy="216" r="1.5" fill="#cbd5e1" />
                                        <circle cx="119" cy="216" r="1.5" fill="#cbd5e1" />

                                        <circle cx="228" cy="216" r="16" fill="#1e293b" />
                                        <circle cx="228" cy="216" r="9" fill="#475569" />
                                        <circle cx="228" cy="216" r="4" fill="#94a3b8" />
                                        <circle cx="228" cy="207" r="1.5" fill="#cbd5e1" />
                                        <circle cx="228" cy="225" r="1.5" fill="#cbd5e1" />
                                        <circle cx="219" cy="216" r="1.5" fill="#cbd5e1" />
                                        <circle cx="237" cy="216" r="1.5" fill="#cbd5e1" />

                                        <!-- exhaust puffs -->
                                        <rect x="52" y="170" width="12" height="7" rx="3.5"
                                            fill="#94a3b8" />
                                        <circle cx="44" cy="164" r="6" fill="#e2e8f0" opacity="0.7" />
                                        <circle cx="36" cy="156" r="4.5" fill="#e2e8f0"
                                            opacity="0.5" />
                                        <circle cx="30" cy="148" r="3" fill="#e2e8f0" opacity="0.3" />

                                        <!-- ===== PERSON - delivery guy waving ===== -->
                                        <!-- legs -->
                                        <rect x="158" y="160" width="14" height="50" rx="7"
                                            fill="#0f766e" />
                                        <rect x="176" y="160" width="14" height="50" rx="7"
                                            fill="#0f766e" />
                                        <!-- pants crease -->
                                        <path d="M165 172 L165 196" stroke="#0d6b62" stroke-width="1.5"
                                            opacity="0.5" />
                                        <path d="M183" y1="172" y2="196" stroke="#0d6b62"
                                            stroke-width="1.5" opacity="0.5" />
                                        <!-- shoes -->
                                        <ellipse cx="164" cy="211" rx="13" ry="6"
                                            fill="#1e293b" />
                                        <ellipse cx="163" cy="209" rx="10" ry="4"
                                            fill="#334155" />
                                        <ellipse cx="184" cy="211" rx="13" ry="6"
                                            fill="#1e293b" />
                                        <ellipse cx="185" cy="209" rx="10" ry="4"
                                            fill="#334155" />
                                        <!-- body torso - uniform teal -->
                                        <ellipse cx="174" cy="130" rx="26" ry="33"
                                            fill="#14b8a6" />
                                        <!-- uniform collar -->
                                        <path d="M155 112 Q174 122 193 112" fill="none" stroke="#0d9488"
                                            stroke-width="2" />
                                        <!-- uniform badge -->
                                        <rect x="178" y="118" width="16" height="20" rx="3"
                                            fill="rgba(255,255,255,.3)" />
                                        <line x1="186" y1="112" x2="186" y2="118"
                                            stroke="rgba(255,255,255,.5)" stroke-width="1.5" />
                                        <!-- uniform button line -->
                                        <line x1="174" y1="120" x2="174" y2="156"
                                            stroke="#0d9488" stroke-width="1.5" stroke-dasharray="3,3"
                                            opacity="0.7" />
                                        <!-- arm left - big wave -->
                                        <path d="M150 116 Q128 96 118 70" stroke="#14b8a6" stroke-width="14"
                                            stroke-linecap="round" />
                                        <!-- waving hand -->
                                        <circle cx="116" cy="66" r="13" fill="#fcd9b6" />
                                        <!-- fingers spread wave -->
                                        <path d="M108 60 Q107 52 111 51 Q115 50 115 58" fill="none"
                                            stroke="#fcd9b6" stroke-width="5" stroke-linecap="round" />
                                        <path d="M114 57 Q113 48 117 47 Q121 46 121 55" fill="none"
                                            stroke="#fcd9b6" stroke-width="5" stroke-linecap="round" />
                                        <path d="M120 58 Q120 49 124 49 Q128 49 127 57" fill="none"
                                            stroke="#fcd9b6" stroke-width="5" stroke-linecap="round" />
                                        <!-- arm right - holding clipboard -->
                                        <path d="M198 116 Q220 128 232 146" stroke="#14b8a6" stroke-width="14"
                                            stroke-linecap="round" />
                                        <!-- clipboard -->
                                        <rect x="228" y="138" width="32" height="42" rx="4"
                                            fill="#fde68a" />
                                        <rect x="234" y="132" width="18" height="10" rx="3"
                                            fill="#f59e0b" />
                                        <!-- clipboard lines -->
                                        <rect x="232" y="148" width="24" height="3" rx="1.5"
                                            fill="#f59e0b" opacity="0.6" />
                                        <rect x="232" y="155" width="24" height="3" rx="1.5"
                                            fill="#f59e0b" opacity="0.6" />
                                        <rect x="232" y="162" width="18" height="3" rx="1.5"
                                            fill="#f59e0b" opacity="0.6" />
                                        <!-- checkmark on clipboard -->
                                        <path d="M234 172 L238 176 L246 168" fill="none" stroke="#16a34a"
                                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <!-- HEAD -->
                                        <circle cx="174" cy="90" r="23" fill="#fcd9b6" />
                                        <!-- delivery cap -->
                                        <path d="M152 86 Q153 68 174 65 Q195 68 196 86" fill="#1d4ed8" />
                                        <rect x="150" y="83" width="48" height="7" rx="3.5"
                                            fill="#1e40af" />
                                        <!-- cap brim -->
                                        <rect x="146" y="86" width="56" height="6" rx="3"
                                            fill="#1e3a8a" />
                                        <!-- cap logo -->
                                        <circle cx="174" cy="76" r="6" fill="#3b82f6" />
                                        <text x="174" y="79.5" font-size="7" font-weight="800" fill="#fff"
                                            text-anchor="middle">GO</text>
                                        <!-- ears -->
                                        <ellipse cx="151" cy="91" rx="5" ry="7"
                                            fill="#f5c89a" />
                                        <ellipse cx="197" cy="91" rx="5" ry="7"
                                            fill="#f5c89a" />
                                        <!-- friendly eyes -->
                                        <circle cx="165" cy="91" r="4" fill="#1e293b" />
                                        <circle cx="183" cy="91" r="4" fill="#1e293b" />
                                        <circle cx="166.5" cy="89.5" r="1.5" fill="#fff" />
                                        <circle cx="184.5" cy="89.5" r="1.5" fill="#fff" />
                                        <!-- kind smile -->
                                        <path d="M163 100 Q174 108 185 100" fill="none" stroke="#c27d4a"
                                            stroke-width="2.5" stroke-linecap="round" />
                                        <path d="M166 101 Q174 107 182 101" fill="#fff" />
                                        <!-- blush -->
                                        <ellipse cx="158" cy="97" rx="6" ry="3.5"
                                            fill="#fca5a5" opacity="0.5" />
                                        <ellipse cx="190" cy="97" rx="6" ry="3.5"
                                            fill="#fca5a5" opacity="0.5" />

                                        <!-- location pin floating -->
                                        <path d="M296 48 Q296 30 312 30 Q328 30 328 48 Q328 60 312 76 Q296 60 296 48Z"
                                            fill="#f97316" />
                                        <circle cx="312" cy="46" r="7" fill="#fff" />
                                        <!-- pin shadow -->
                                        <ellipse cx="312" cy="77" rx="6" ry="3"
                                            fill="rgba(0,0,0,.15)" />

                                        <!-- route dots -->
                                        <circle cx="286" cy="110" r="4" fill="#fde68a" opacity="0.8" />
                                        <circle cx="298" cy="110" r="4" fill="#fde68a" opacity="0.8" />
                                        <circle cx="310" cy="110" r="4" fill="#fde68a" opacity="0.8" />

                                        <!-- speed lines on truck -->
                                        <line x1="48" y1="158" x2="68" y2="158"
                                            stroke="rgba(255,255,255,.2)" stroke-width="2" stroke-linecap="round" />
                                        <line x1="42" y1="166" x2="60" y2="166"
                                            stroke="rgba(255,255,255,.15)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <line x1="46" y1="174" x2="62" y2="174"
                                            stroke="rgba(255,255,255,.1)" stroke-width="1" stroke-linecap="round" />
                                    </svg>
                                </div>

                                <div class="cs-left">
                                    <div class="cs-badge b2">
                                        <div class="cs-dot"></div>GRATIS ONGKIR
                                    </div>
                                    <div class="cs-title">Belanja <span>bebas ongkos</span><br>ke seluruh Indonesia
                                    </div>
                                    <div class="cs-sub">Minimal pembelian Rp99.000. Berlaku untuk semua metode
                                        pembayaran.</div>
                                    <a href="#produk" class="cs-btn">Belanja Sekarang <span
                                            class="cs-arr">›</span></a>
                                </div>
                                <div class="cs-right">
                                    <div class="cs-icon fl2">📦</div>
                                    <div class="cs-icon big fl3">🚚</div>
                                    <div class="cs-icon fl1">📍</div>
                                </div>
                            </div>

                        </div>

                        <button class="cs-nav cs-prev" id="csPrev">‹</button>
                        <button class="cs-nav cs-next" id="csNext">›</button>
                        <div class="cs-dots" id="csDots">
                            <div class="cs-pip active" data-i="0"></div>
                            <div class="cs-pip" data-i="1"></div>
                            <div class="cs-pip" data-i="2"></div>
                        </div>
                        <div class="cs-progress" id="csProgress"></div>
                    </div>
                </section>

                {{-- KATEGORI --}}
                @if ($categories->count() > 0)
                    <section class="category-section">
                        <div class="section-header">
                            <div class="section-title"><span class="dot"></span> Kategori Pilihan</div>
                        </div>
                        <div class="category-grid">
                            <a href="{{ route('dashboard') }}"
                                class="cat-card {{ !request('category') ? 'active-cat' : '' }}">
                                <div class="cat-icon" style="background: var(--mp-blue-600); color: #fff;">🛍️</div>
                                <span class="cat-name">Semua</span>
                            </a>
                            @foreach ($categories as $cat)
                                @php $isActive = request('category') == $cat->category_id; @endphp
                                <a href="{{ route('dashboard', ['category' => $cat->category_id]) }}"
                                    class="cat-card {{ $isActive ? 'active-cat' : '' }}">
                                    <div class="cat-icon">{{ $cat->icon ?? '🏷️' }}</div>
                                    <span class="cat-name">{{ $cat->nama_kategori ?? $cat->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- PRODUK --}}
                <section class="products-section" id="produk">
                    <div class="section-header">
                        <div class="section-title">
                            <span class="dot"></span>
                            Katalog Produk
                            <span style="font-size:13px;font-weight:500;color:var(--mp-gray-500);">
                                ({{ $products->count() }} produk)
                            </span>
                        </div>
                        <form action="{{ route('dashboard') }}" method="GET">
                            <select name="sort" class="filter-select" onchange="this.form.submit()">
                                <option value="terbaru"
                                    {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga
                                    Terendah</option>
                                <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga
                                    Tertinggi</option>
                            </select>
                        </form>
                    </div>

                    <div class="product-grid">
                        @forelse ($products as $product)
                            <a href="{{ $product->total_stok <= 0 ? 'javascript:void(0)' : route('products.show', $product->product_id) }}"
                                class="product-card {{ $product->total_stok <= 0 ? 'product-card-disabled' : '' }}">

                                <div class="product-img-wrap">
                                    @if ($product->primaryImage && $product->primaryImage->gambar)
                                        <img src="{{ asset('storage/' . $product->primaryImage->gambar) }}"
                                            alt="{{ $product->nama_produk }}" loading="lazy"
                                            onerror="this.src='https://placehold.co/400x400?text=Produk'">
                                    @else
                                        <div class="product-img-placeholder">👕</div>
                                    @endif
                                    @if ($product->total_stok <= 0)
                                        <span class="product-badge" style="background:#6b7280;">Habis</span>
                                    @elseif ($product->created_at->diffInHours(now()) <= 7)
                                        <span class="product-badge new">Baru</span>
                                    @endif
                                </div>

                                <div class="product-info">
                                    <div class="product-name">{{ $product->nama_produk }}</div>
                                    <div>
                                        <span class="product-price">Rp
                                            {{ number_format($product->harga, 0, ',', '.') }}</span>
                                    </div>
                                    @php
                                        $avg = round($product->reviews_avg_bintang ?? 0, 1);
                                        $full = floor($avg);
                                        $half = $avg - $full >= 0.5 ? 1 : 0;
                                        $empty = 5 - $full - $half;
                                    @endphp
                                    <div
                                        style="display:flex;align-items:center;gap:3px;margin-top:5px;flex-wrap:wrap;">
                                        @for ($i = 0; $i < $full; $i++)
                                            <svg style="width:11px;height:11px;color:#f59e0b;flex-shrink:0;"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        @if ($half)
                                            <svg style="width:11px;height:11px;flex-shrink:0;" viewBox="0 0 20 20">
                                                <defs>
                                                    <linearGradient id="half-{{ $product->product_id }}">
                                                        <stop offset="50%" stop-color="#f59e0b" />
                                                        <stop offset="50%" stop-color="#e2e8f0" />
                                                    </linearGradient>
                                                </defs>
                                                <path fill="url(#half-{{ $product->product_id }})"
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endif
                                        @for ($i = 0; $i < $empty; $i++)
                                            <svg style="width:11px;height:11px;color:#e2e8f0;flex-shrink:0;"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        @if ($product->reviews_count > 0)
                                            <span
                                                style="font-size:10px;font-weight:700;color:#64748b;">{{ $avg }}</span>
                                            <span
                                                style="font-size:10px;color:#94a3b8;">({{ $product->reviews_count }})</span>
                                        @else
                                            <span style="font-size:10px;color:#cbd5e1;">Belum ada ulasan</span>
                                        @endif
                                        @if (($product->terjual ?? 0) > 0)
                                            <span style="font-size:10px;color:#94a3b8;">· {{ $product->terjual }}+
                                                terjual</span>
                                        @endif
                                    </div>
                                    @if ($product->store)
                                        <div class="product-loc">📍
                                            {{ $product->store->nama_toko ?? $product->store->name }}</div>
                                    @endif

                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; position: relative;">
                                        @php $stok = $product->total_stok; @endphp
                                        <div
                                            class="product-stock {{ $stok <= 0 ? 'out' : ($stok <= 5 ? 'low' : 'ok') }}">
                                            @if ($stok <= 0)
                                                Stok habis
                                            @elseif ($stok <= 5)
                                                Sisa {{ $stok }} item
                                            @else
                                                Stok tersedia
                                            @endif
                                        </div>
                                        <div style="position: relative;">
                                            <button class="btn-more"
                                                onclick="toggleDropdown(event, {{ $product->product_id }})">···</button>
                                            <div class="more-dropdown" id="dropdown-{{ $product->product_id }}">
                                                @php $inWishlist = in_array($product->product_id, $wishlistIds ?? []); @endphp
                                                <button class="more-dropdown-item"
                                                    id="btn-wishlist-{{ $product->product_id }}"
                                                    onclick="addToWishlist(event, {{ $product->product_id }})">
                                                    {{ $inWishlist ? 'Hapus dari Wishlist' : 'Masukkan ke Wishlist' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="empty-products">
                                <div class="empty-icon">🛍️</div>
                                <p>Belum ada produk yang tersedia.</p>
                                @if (request('category') || request('search'))
                                    <a href="{{ route('dashboard') }}"
                                        style="color:var(--mp-blue-600);font-weight:600;font-size:13px;">Reset
                                        Filter</a>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>
        </div>

        {{-- Toast Notif Wishlist --}}
        <div class="wl-toast" id="wl-toast">
            <span id="wl-toast-msg">Berhasil ditambahkan ke wishlist!</span>
        </div>

        <script>
            function toggleDropdown(e, id) {
                e.preventDefault();
                e.stopPropagation();
                const el = document.getElementById('dropdown-' + id);
                const isOpen = el.classList.contains('show');
                document.querySelectorAll('.more-dropdown').forEach(d => d.classList.remove('show'));
                if (!isOpen) el.classList.add('show');
            }

            document.addEventListener('click', () => {
                document.querySelectorAll('.more-dropdown').forEach(d => d.classList.remove('show'));
            });

            function addToWishlist(e, productId) {
                e.preventDefault();
                e.stopPropagation();
                const btn = document.getElementById('btn-wishlist-' + productId);
                document.querySelectorAll('.more-dropdown').forEach(d => d.classList.remove('show'));
                fetch('{{ route('wishlist.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('Network error');
                        return r.json();
                    })
                    .then(data => {
                        if (data.status === 'added' || data.wishlisted) {
                            showToast('❤️', 'Ditambahkan ke wishlist!');
                            if (btn) btn.textContent = 'Hapus dari Wishlist';
                        } else {
                            showToast('💔', 'Dihapus dari wishlist');
                            if (btn) btn.textContent = 'Masukkan ke Wishlist';
                        }
                    })
                    .catch(err => {
                        console.error('Wishlist error:', err);
                        showToast('❌', 'Gagal, coba lagi.');
                    });
            }

            let toastTimer = null;

            function showToast(icon, msg) {
                const toast = document.getElementById('wl-toast');
                const iconEl = document.getElementById('wl-toast-icon');
                const msgEl = document.getElementById('wl-toast-msg');
                if (iconEl) iconEl.textContent = icon;
                if (msgEl) msgEl.textContent = msg;
                toast.classList.add('show');
                if (toastTimer) clearTimeout(toastTimer);
                toastTimer = setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }

            // CAROUSEL
            (function() {
                const track = document.getElementById('csTrack');
                if (!track) return;
                const pips = document.querySelectorAll('.cs-pip');
                const prog = document.getElementById('csProgress');
                const total = 3;
                const DUR = 4000;
                let cur = 0,
                    timer;

                function goTo(n) {
                    cur = (n + total) % total;
                    track.style.transform = `translateX(-${cur * 100}%)`;
                    pips.forEach((p, i) => p.classList.toggle('active', i === cur));
                    prog.style.transition = 'none';
                    prog.style.width = '0%';
                    prog.getBoundingClientRect();
                    prog.style.transition = `width ${DUR}ms linear`;
                    prog.style.width = '100%';
                }

                function startAuto() {
                    clearInterval(timer);
                    timer = setInterval(() => goTo(cur + 1), DUR);
                }

                document.getElementById('csNext').onclick = () => {
                    goTo(cur + 1);
                    startAuto();
                };
                document.getElementById('csPrev').onclick = () => {
                    goTo(cur - 1);
                    startAuto();
                };
                pips.forEach(p => p.addEventListener('click', () => {
                    goTo(+p.dataset.i);
                    startAuto();
                }));

                goTo(0);
                startAuto();
            })();
        </script>

    @endif

</x-app-layout>
