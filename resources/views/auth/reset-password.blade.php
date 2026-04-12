<x-guest-layout>
    <style>
        .min-h-screen>div:first-child {
            max-width: 100% !important;
            padding: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
    </style>

    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] p-4 md:p-8">
        <div class="w-full max-w-5xl flex flex-col md:flex-row bg-[#0a1d37] overflow-hidden rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)]">

            {{-- Left Form --}}
            <div class="w-full md:w-1/2 p-10 md:p-16 flex flex-col justify-center bg-white">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-4xl font-black tracking-tighter text-gray-900 uppercase leading-none italic">
                        MAMPANG<br><span class="text-indigo-600">STORE</span>
                    </h2>
                    <div class="mt-4">
                        <p class="text-gray-700 font-bold text-xl">Reset Password</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold mt-1">Buat password baru kamu</p>
                        <div class="h-1 w-12 bg-indigo-600 mt-2 mx-auto md:mx-0"></div>
                    </div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2">Email</label>
                        <input id="email" type="email" name="email"
                            value="{{ old('email', $request->email) }}"
                            required autofocus autocomplete="username"
                            class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-4 px-5 text-sm transition-all bg-gray-50/50"
                            placeholder="Masukkan email kamu" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2">Password Baru</label>
                        <div class="relative">
                            <input id="password" type="password" name="password"
                                required autocomplete="new-password"
                                oninput="checkStrength(this.value); checkMatch()"
                                class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-4 px-5 pr-12 text-sm transition-all bg-gray-50/50"
                                placeholder="Minimal 8 karakter" />
                            <button type="button" onclick="togglePw('password', 'eye1')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 transition">
                                <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        {{-- Strength Bar --}}
                        <div class="mt-2 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div id="strength-fill" class="h-full rounded-full transition-all duration-300" style="width:0%"></div>
                        </div>
                        <p id="strength-label" class="text-[11px] mt-1 font-bold"></p>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                required autocomplete="new-password"
                                oninput="checkMatch()"
                                class="block w-full border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-2xl py-4 px-5 pr-12 text-sm transition-all bg-gray-50/50"
                                placeholder="Ulangi password baru" />
                            <button type="button" onclick="togglePw('password_confirmation', 'eye2')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 transition">
                                <svg id="eye2" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p id="match-label" class="text-[11px] mt-1 font-bold"></p>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-black text-white font-black py-5 rounded-2xl tracking-[0.2em] shadow-2xl shadow-indigo-200 transition-all transform active:scale-[0.96] uppercase text-xs">
                            Reset Password Sekarang
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em]">
                            Ingat password?
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-black ms-1 border-b-2 border-indigo-100 hover:border-black transition-all">Masuk</a>
                        </p>
                    </div>
                </form>
            </div>

            {{-- Right Illustration --}}
            <div class="hidden md:flex md:w-1/2 bg-[#0a1d37] items-center justify-center p-16 relative overflow-hidden">
                <div class="absolute w-[500px] h-[500px] bg-indigo-500/10 rounded-full -top-20 -right-20 blur-3xl"></div>
                <div class="absolute w-[300px] h-[300px] bg-indigo-400/5 rounded-full -bottom-20 -left-20 blur-2xl"></div>
                <div class="relative z-10 text-center">
                    <img src="{{ asset('assets/komssss.png') }}" alt="Illustration"
                        class="max-w-full h-auto drop-shadow-[0_35px_35px_rgba(0,0,0,0.6)]">
                    <div class="mt-8">
                        <p class="text-indigo-300/60 text-[10px] tracking-[0.5em] font-bold uppercase italic">Mampang Pedia</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePw(inputId, eyeId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function checkStrength(val) {
            const fill = document.getElementById('strength-fill');
            const label = document.getElementById('strength-label');
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const pct = (score / 4) * 100;
            const colors = ['#ef4444', '#f97316', '#22c55e', '#6366f1'];
            const labels = ['Lemah', 'Cukup', 'Kuat', 'Sangat kuat'];
            const textColors = ['text-red-500', 'text-orange-500', 'text-green-500', 'text-indigo-600'];

            fill.style.width = pct + '%';
            fill.style.background = score > 0 ? colors[score - 1] : 'transparent';
            label.textContent = score > 0 ? labels[score - 1] : '';
            label.className = 'text-[11px] mt-1 font-bold ' + (score > 0 ? textColors[score - 1] : '');
        }

        function checkMatch() {
            const pw = document.getElementById('password').value;
            const pw2 = document.getElementById('password_confirmation').value;
            const label = document.getElementById('match-label');
            if (!pw2) { label.textContent = ''; return; }
            if (pw === pw2) {
                label.textContent = '✓ Password cocok';
                label.className = 'text-[11px] mt-1 font-bold text-green-500';
            } else {
                label.textContent = '✗ Password tidak cocok';
                label.className = 'text-[11px] mt-1 font-bold text-red-500';
            }
        }
    </script>
</x-guest-layout>