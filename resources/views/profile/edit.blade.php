<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Akun Saya</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-6">
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-800 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl font-bold text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm">
                    <p class="font-bold mb-1">⚠ Ada yang belum lengkap:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon/WA</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', auth()->user()->no_telp) }}"
                                class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm"
                                inputmode="numeric" pattern="[0-9+\-\s]*"
                                oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}"
                                class="w-full border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed shadow-sm"
                                disabled>
                        </div>
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <hr class="border-gray-100">
                        <div>
                            <h3 class="text-base font-black text-gray-800 mb-1">🏪 Informasi Toko</h3>
                            @if (auth()->user()->store)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-blue-50 border border-blue-100 p-5 rounded-2xl">
                                    <div>
                                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nama
                                            Toko</span>
                                        <p class="font-black text-gray-800 mt-1">{{ auth()->user()->store->nama_toko }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                                            Alamat Toko</span>
                                        @if (auth()->user()->store->province_id)
                                            <p class="text-xs text-green-600 font-bold mt-2">✅ Alamat toko sudah
                                                tersimpan</p>
                                        @else
                                            <p class="text-xs text-red-500 font-bold mt-2">⚠ Belum diisi — ongkir tidak
                                                akan terhitung!</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-100 p-5 rounded-2xl">
                                    <p class="text-sm text-red-500 font-bold">⚠ Toko belum terdaftar! Jalankan
                                        StoreSeeder dulu.</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if (auth()->user()->role !== 'admin')
                        <hr class="border-gray-100">
                        <div
                            class="bg-blue-50 border border-blue-100 rounded-2xl p-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-blue-400 mb-1">Saldo Refund
                                </p>
                                <p class="text-2xl font-black text-blue-700">Rp
                                    {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-400 mt-1">Saldo didapat dari pembatalan pesanan.</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->role !== 'admin')
                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-black text-gray-800">📍 Alamat Pengiriman</p>
                                <p class="text-xs text-gray-400 mt-0.5">Kelola beberapa alamat pengiriman kamu.</p>
                            </div>
                            <a href="{{ route('addresses.index') }}"
                                class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-black rounded-xl hover:bg-blue-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                Kelola Alamat
                            </a>
                        </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
