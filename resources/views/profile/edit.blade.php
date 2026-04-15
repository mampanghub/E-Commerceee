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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm">
                    <p class="font-bold mb-1">⚠ Ada yang belum lengkap:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div id="toast-overlay"
                    style="position:fixed;inset:0;background:rgba(0,0,0,0.18);display:flex;align-items:center;justify-content:center;z-index:9999;">
                    <div
                        style="background:white;border-radius:20px;padding:2.5rem 3rem;text-align:center;min-width:280px;max-width:360px;box-shadow:0 8px 32px rgba(0,0,0,0.10);">
                        <div
                            style="width:64px;height:64px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6L9 17l-5-5" />
                            </svg>
                        </div>
                        <p style="font-size:18px;font-weight:800;color:#111;margin:0 0 6px;">Berhasil Disimpan!</p>
                        <p style="font-size:14px;color:#6b7280;margin:0 0 1.75rem;">{{ session('success') }}</p>
                        <button onclick="document.getElementById('toast-overlay').style.display='none'"
                            style="padding:10px 28px;background:#f0fdf4;color:#16a34a;font-weight:800;font-size:14px;border:1.5px solid #bbf7d0;border-radius:12px;cursor:pointer;">
                            Oke
                        </button>
                    </div>
                </div>
            @endif

            {{-- CARD 1: Form Profil --}}
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

                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-black text-gray-800">Alamat Pengiriman</p>
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

            {{-- CARD 2: Form Toko (hanya admin, terpisah dari form profil) --}}
            @if (auth()->user()->role === 'admin')
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                    <div class="mb-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Pengaturan</p>
                        <h3 class="text-lg font-black text-gray-900">Informasi Toko</h3>
                    </div>

                    @if (auth()->user()->store)
                        <form method="POST" action="{{ route('profile.store.update') }}" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Toko</label>
                                    <input type="text" name="nama_toko"
                                        value="{{ old('nama_toko', auth()->user()->store->nama_toko) }}"
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                                    <input type="text" name="alamat"
                                        value="{{ old('alamat', auth()->user()->store->alamat) }}"
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Toko</label>
                                <textarea name="deskripsi" rows="2" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm">{{ old('deskripsi', auth()->user()->store->deskripsi) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                                    <select name="province_id" id="store_province_id"
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm"
                                        required>
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach (\Laravolt\Indonesia\Models\Province::orderBy('name')->get() as $prov)
                                            <option value="{{ $prov->code }}"
                                                {{ old('province_id', auth()->user()->store->province_id) == $prov->code ? 'selected' : '' }}>
                                                {{ $prov->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota/Kabupaten</label>
                                    <select name="city_id" id="store_city_id"
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm"
                                        required>
                                        <option value="">-- Pilih Kota --</option>
                                        @if (auth()->user()->store->city_id)
                                            @foreach (\Laravolt\Indonesia\Models\City::where('province_code', auth()->user()->store->province_id)->orderBy('name')->get() as $city)
                                                <option value="{{ $city->code }}"
                                                    {{ auth()->user()->store->city_id == $city->code ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                                    <select name="district_id" id="store_district_id"
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm"
                                        required>
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @if (auth()->user()->store->district_id)
                                            @foreach (\Laravolt\Indonesia\Models\District::where('city_code', auth()->user()->store->city_id)->orderBy('name')->get() as $dist)
                                                <option value="{{ $dist->code }}"
                                                    {{ auth()->user()->store->district_id == $dist->code ? 'selected' : '' }}>
                                                    {{ $dist->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kelurahan/Desa</label>
                                    <select name="village_id" id="store_village_id"
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 shadow-sm"
                                        required>
                                        <option value="">-- Pilih Kelurahan --</option>
                                        @if (auth()->user()->store->village_id)
                                            @foreach (\Laravolt\Indonesia\Models\Village::where('district_code', auth()->user()->store->district_id)->orderBy('name')->get() as $vil)
                                                <option value="{{ $vil->code }}"
                                                    {{ auth()->user()->store->village_id == $vil->code ? 'selected' : '' }}>
                                                    {{ $vil->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="px-8 py-3 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                                    Simpan Informasi Toko
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-red-50 border border-red-100 p-5 rounded-2xl">
                            <p class="text-sm text-red-500 font-bold">Toko belum terdaftar! Jalankan StoreSeeder dulu.
                            </p>
                        </div>
                    @endif
                </div>

                <script>
                    document.getElementById('store_province_id')?.addEventListener('change', function() {
                        const provId = this.value;
                        ['store_city_id', 'store_district_id', 'store_village_id'].forEach(id => {
                            document.getElementById(id).innerHTML = '<option value="">-- Pilih --</option>';
                        });
                        if (!provId) return;
                        fetch(`/api/get-cities?province_id=${provId}`)
                            .then(r => r.json())
                            .then(data => {
                                const sel = document.getElementById('store_city_id');
                                data.forEach(c => sel.insertAdjacentHTML('beforeend',
                                    `<option value="${c.code}">${c.name}</option>`));
                            });
                    });

                    document.getElementById('store_city_id')?.addEventListener('change', function() {
                        const cityId = this.value;
                        ['store_district_id', 'store_village_id'].forEach(id => {
                            document.getElementById(id).innerHTML = '<option value="">-- Pilih --</option>';
                        });
                        if (!cityId) return;
                        fetch(`/api/get-districts?city_id=${cityId}`)
                            .then(r => r.json())
                            .then(data => {
                                const sel = document.getElementById('store_district_id');
                                data.forEach(d => sel.insertAdjacentHTML('beforeend',
                                    `<option value="${d.code}">${d.name}</option>`));
                            });
                    });

                    document.getElementById('store_district_id')?.addEventListener('change', function() {
                        const distId = this.value;
                        document.getElementById('store_village_id').innerHTML = '<option value="">-- Pilih --</option>';
                        if (!distId) return;
                        fetch(`/api/get-villages?district_id=${distId}`)
                            .then(r => r.json())
                            .then(data => {
                                const sel = document.getElementById('store_village_id');
                                data.forEach(v => sel.insertAdjacentHTML('beforeend',
                                    `<option value="${v.code}">${v.name}</option>`));
                            });
                    });
                </script>
            @endif

        </div>
    </div>
</x-app-layout>
