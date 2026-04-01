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
                        <label
                            class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2">
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

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-black text-white font-black py-5 rounded-2xl tracking-[0.2em] shadow-2xl shadow-indigo-200 transition-all transform active:scale-[0.96] uppercase text-xs">
                            LOG IN SEKARANG
                        </button>
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
