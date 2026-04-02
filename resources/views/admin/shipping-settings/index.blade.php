<x-app-layout>
    <div class="min-h-screen bg-blue-50/40">

        {{-- HEADER --}}
        <div class="bg-white border-b border-blue-100">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('profile.edit') }}"
                        class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500">Admin</p>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Ongkir</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            @if(session('success'))
            <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm font-black text-emerald-700">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- FORM --}}
                <div class="lg:col-span-2">
                    <form action="{{ route('admin.shipping-settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">

                            {{-- Tarif per KM --}}
                            <div class="p-6 border-b border-slate-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-9 h-9 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800">Tarif per Kilometer</p>
                                        <p class="text-xs text-slate-400">Biaya per km jarak antar provinsi toko dan pembeli.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-black text-slate-400 shrink-0">Rp</span>
                                    <input type="number" name="tarif_per_km"
                                        value="{{ old('tarif_per_km', $settings['tarif_per_km']->value ?? 1500) }}"
                                        min="0" step="100"
                                        class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-sm font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all @error('tarif_per_km') border-red-300 @enderror">
                                    <span class="text-sm font-black text-slate-400 shrink-0">/ km</span>
                                </div>
                                @error('tarif_per_km')
                                <p class="text-xs text-red-500 font-bold mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tarif per 500gr --}}
                            <div class="p-6 border-b border-slate-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-9 h-9 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800">Tarif per 500gr Tambahan</p>
                                        <p class="text-xs text-slate-400">Biaya tiap 500gr setelah 500gr pertama (gratis).</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-black text-slate-400 shrink-0">Rp</span>
                                    <input type="number" name="tarif_per_500gr"
                                        value="{{ old('tarif_per_500gr', $settings['tarif_per_500gr']->value ?? 500) }}"
                                        min="0" step="100"
                                        class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-sm font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all @error('tarif_per_500gr') border-red-300 @enderror">
                                    <span class="text-sm font-black text-slate-400 shrink-0">/ 500gr</span>
                                </div>
                                @error('tarif_per_500gr')
                                <p class="text-xs text-red-500 font-bold mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jarak minimum --}}
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-9 h-9 bg-violet-50 border border-violet-100 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800">Jarak Minimum Pengiriman</p>
                                        <p class="text-xs text-slate-400">Jarak minimum agar ongkir tidak nol walau lokasi sangat dekat.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="jarak_minimum"
                                        value="{{ old('jarak_minimum', $settings['jarak_minimum']->value ?? 5) }}"
                                        min="1" step="1"
                                        class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-sm font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all @error('jarak_minimum') border-red-300 @enderror">
                                    <span class="text-sm font-black text-slate-400 shrink-0">km</span>
                                </div>
                                @error('jarak_minimum')
                                <p class="text-xs text-red-500 font-bold mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-black transition-all shadow-sm shadow-blue-200 active:scale-95">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- PREVIEW --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 sticky top-8">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Preview Kalkulasi</p>
                        <p class="text-xs text-slate-400 mb-5">Simulasi ongkir berdasarkan nilai yang kamu masukkan.</p>

                        <div class="space-y-3 mb-5">
                            <div class="bg-slate-50 rounded-xl p-3.5">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">100km · 1kg</p>
                                <p class="font-black text-slate-900 text-sm" id="preview-1">-</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3.5">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">300km · 2.5kg</p>
                                <p class="font-black text-slate-900 text-sm" id="preview-2">-</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3.5">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Jarak min · 500gr</p>
                                <p class="font-black text-slate-900 text-sm" id="preview-3">-</p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-[10px] text-slate-400 leading-relaxed">
                                <span class="font-black text-slate-500">Formula:</span><br>
                                ongkir = (jarak × tarif/km)<br>
                                + (ceil(berat/500) − 1) × tarif/500gr
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function formatRupiah(num) {
            return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        function hitungOngkir(jarakKm, beratGram, tarifKm, tarif500gr, jarakMin) {
            const jarak      = Math.max(jarakKm, jarakMin);
            const biayaJarak = Math.ceil(jarak) * tarifKm;
            const kelipatan  = Math.max(0, Math.ceil(beratGram / 500) - 1);
            return biayaJarak + kelipatan * tarif500gr;
        }
        function updatePreview() {
            const tarifKm    = parseFloat(document.querySelector('[name=tarif_per_km]').value) || 0;
            const tarif500gr = parseFloat(document.querySelector('[name=tarif_per_500gr]').value) || 0;
            const jarakMin   = parseFloat(document.querySelector('[name=jarak_minimum]').value) || 1;
            document.getElementById('preview-1').textContent = formatRupiah(hitungOngkir(100,     1000, tarifKm, tarif500gr, jarakMin));
            document.getElementById('preview-2').textContent = formatRupiah(hitungOngkir(300,     2500, tarifKm, tarif500gr, jarakMin));
            document.getElementById('preview-3').textContent = formatRupiah(hitungOngkir(jarakMin, 500, tarifKm, tarif500gr, jarakMin));
        }
        document.querySelectorAll('input[type=number]').forEach(el => el.addEventListener('input', updatePreview));
        updatePreview();
    </script>
</x-app-layout>
