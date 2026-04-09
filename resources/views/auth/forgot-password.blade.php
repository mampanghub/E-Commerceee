<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
        <div class="w-full max-w-md">

            {{-- LOGO / BRAND --}}
            <div class="text-center mb-8">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Admin Panel</p>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Lupa Password?</h1>
                <p class="text-sm text-slate-400 mt-2 font-medium">
                    Tenang, kami kirimkan link reset ke email kamu.
                </p>
            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 rounded-2xl px-4 py-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-emerald-700">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="off"
                                required
                                autofocus
                                placeholder="kamu@email.com"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-800 placeholder:text-slate-300 placeholder:font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-300 transition-all @error('email') border-rose-300 bg-rose-50 focus:ring-rose-500/20 focus:border-rose-300 @enderror"
                            />
                        </div>
                        @error('email')
                            <div class="mt-2 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs font-bold text-rose-500">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 active:scale-[0.98] text-white text-sm font-black py-3 px-6 rounded-2xl transition-all duration-150 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim Link Reset
                    </button>

                </form>
            </div>

            {{-- Back to login --}}
            <div class="text-center mt-6">
                <a href="{{ route('login') }}"
                    class="text-xs font-black text-slate-400 hover:text-slate-600 transition-all inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Login
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
