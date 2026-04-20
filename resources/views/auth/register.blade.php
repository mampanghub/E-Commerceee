<x-guest-layout>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div
            class="flex flex-col md:flex-row min-h-[600px] bg-[#0a1d37] overflow-hidden rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)]">

            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white">
                <div class="mb-8">
                    <h2 class="text-3xl font-black tracking-tighter text-gray-900 uppercase leading-none italic">
                        JOIN THE<br><span class="text-indigo-600">ATELIER</span>
                    </h2>
                    <div class="mt-4">
                        <p class="text-gray-700 font-bold text-lg">Buat Akun Baru</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold mt-1">Mampang Kasir
                            Edition</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-1">Nama
                            Lengkap</label>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus
                            autocomplete="name"
                            class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-3 px-5 text-sm transition-all bg-gray-50/50"
                            placeholder="Nama Anda" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-1">Email</label>
                        <input id="email" type="email" name="email" :value="old('email')" required
                            autocomplete="username"
                            class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-3 px-5 text-sm transition-all bg-gray-50/50"
                            placeholder="email@anda.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-1">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-3 px-5 text-sm transition-all bg-gray-50/50"
                                placeholder="••••••••" />
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-1">Konfirmasi</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-3 px-5 text-sm transition-all bg-gray-50/50"
                                placeholder="••••••••" />
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-black text-white font-black py-4 rounded-2xl tracking-[0.2em] shadow-xl shadow-indigo-100 transition-all transform active:scale-[0.96] uppercase text-xs">
                            DAFTAR SEKARANG
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                            Sudah punya akun?
                            <a href="{{ route('login') }}"
                                class="text-indigo-600 hover:text-black ms-1 border-b-2 border-indigo-100 hover:border-black transition-all">Log
                                In</a>
                        </p>
                    </div>
                </form>
            </div>

            <div class="hidden md:flex md:w-1/2 bg-[#0a1d37] items-center justify-center p-16 relative overflow-hidden">
                <div class="absolute w-[500px] h-[500px] bg-indigo-500/10 rounded-full -top-20 -right-20 blur-3xl">
                </div>

                <div class="relative z-10 text-center">
                    <img src="{{ asset('assets/komssss.png') }}" alt="Illustration"
                        class="max-w-full h-auto drop-shadow-[0_35px_35px_rgba(0,0,0,0.6)]">

                    <div class="mt-8 text-indigo-300/40 text-[10px] tracking-[0.4em] font-bold uppercase italic">
                        Mampang Pedia
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</x-guest-layout>
