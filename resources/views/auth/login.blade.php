<x-guest-layout>
    <style>
        /* Menghilangkan paksaan max-width dari parent Laravel Breeze */
        .min-h-screen>div:first-child {
            max-width: 100% !important;
            padding: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
    </style>

    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] p-4 md:p-8">
        <div
            class="w-full max-w-5xl flex flex-col md:flex-row bg-[#0a1d37] overflow-hidden rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)]">

            <div class="w-full md:w-1/2 p-10 md:p-16 flex flex-col justify-center bg-white">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-4xl font-black tracking-tighter text-gray-900 uppercase leading-none italic">
                        MAMPANG<br><span class="text-indigo-600">STORE</span>
                    </h2>
                    <div class="mt-4 inline-block md:block">
                        <p class="text-gray-700 font-bold text-xl">Masuk ke Dashboard</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold mt-1">Mampang
                            Edition</p>
                        <div class="h-1 w-12 bg-indigo-600 mt-2 mx-auto md:mx-0"></div>
                    </div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2">
                            Email</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-4 px-5 text-sm transition-all bg-gray-50/50"
                            placeholder="Masukkan Username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2">Password</label>
                        <input id="password" type="password" name="password" required
                            class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-4 px-5 text-sm transition-all bg-gray-50/50"
                            placeholder="Masukkan Password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-tighter">
                        @if (Route::has('password.request'))
                            <a class="text-indigo-600 hover:text-indigo-800 underline decoration-indigo-200 underline-offset-4 transition"
                                href="{{ route('password.request') }}">Lupa Password?</a>
                        @endif
                    </div>

                    <div class="pt-6 space-y-3">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-black text-white font-black py-5 rounded-2xl tracking-[0.2em] shadow-2xl shadow-indigo-200 transition-all transform active:scale-[0.96] uppercase text-xs">
                            LOG IN SEKARANG
                        </button>

                        <div class="flex items-center gap-3 my-2">
                            <div class="flex-1 h-px bg-gray-200"></div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">atau</span>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>

                        <a href="{{ route('auth.google') }}"
                            class="flex items-center justify-center gap-3 w-full border-2 border-gray-100 hover:border-indigo-500 bg-gray-50/50 hover:bg-indigo-50 text-gray-700 font-black py-4 rounded-2xl tracking-[0.1em] transition-all transform active:scale-[0.96] uppercase text-xs">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Masuk dengan Google
                        </a>
                    </div>

                    <div class="text-center mt-10">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                            Baru di sini?
                            <a href="{{ route('register') }}"
                                class="text-indigo-600 hover:text-black ms-1 border-b-2 border-indigo-100 hover:border-black transition-all">Buat
                                Akun Baru</a>
                        </p>
                    </div>
                </form>
            </div>

            <div class="hidden md:flex md:w-1/2 bg-[#0a1d37] items-center justify-center p-16 relative overflow-hidden">
                <div class="absolute w-[500px] h-[500px] bg-indigo-500/10 rounded-full -top-20 -right-20 blur-3xl">
                </div>
                <div class="absolute w-[300px] h-[300px] bg-indigo-400/5 rounded-full -bottom-20 -left-20 blur-2xl">
                </div>

                <div class="relative z-10 text-center">
                    <img src="{{ asset('assets/komssss.png') }}" alt="Illustration"
                        class="max-w-full h-auto drop-shadow-[0_35px_35px_rgba(0,0,0,0.6)]">

                    <div class="mt-8">
                        <p
                            class="text-indigo-300/60 text-[10px] tracking-[0.5em] font-bold uppercase italic tracking-widest">
                            Mampang Pedia
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
