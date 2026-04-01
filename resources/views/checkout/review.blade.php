<x-app-layout>
    <div class="py-12 bg-slate-50/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="javascript:history.back()"
                    class="group inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-all">
                    <div class="p-2 bg-white rounded-xl shadow-sm mr-3 group-hover:bg-indigo-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    Kembali
                </a>
            </div>

            <h1 class="text-3xl font-black text-slate-900 mb-8 tracking-tighter italic">Ringkasan Pesanan</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KIRI --}}
                <div class="lg:col-span-2 flex flex-col gap-6">

                    {{-- Produk --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Produk</h2>
                        <div class="flex flex-col gap-4">
                            @foreach ($items as $item)
                            <div class="flex items-center gap-4">
                                <img src="{{ $item['gambar'] ? asset('storage/' . $item['gambar']) : 'https://placehold.co/100x100?text=No+Image' }}"
                                    class="w-16 h-16 object-cover rounded-2xl bg-slate-100"
                                    onerror="this.src='https://placehold.co/100x100?text=No+Image'">
                                <div class="flex-1">
                                    <p class="font-black text-slate-900 text-sm">{{ $item['product']->nama_produk }}</p>
                                    @if ($item['variant'])
                                    <p class="text-xs text-indigo-500 font-bold">{{ $item['variant']->nama_varian }}</p>
                                    @endif
                                    <p class="text-xs text-slate-400 mt-1">{{ $item['jumlah'] }} x Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                                </div>
                                <p class="font-black text-slate-900 text-sm">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PILIH ALAMAT --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Alamat Pengiriman</h2>
                            <button type="button" onclick="openAddressModal({ action: '{{ route('addresses.store') }}', onSuccess: reloadAddresses })"
                                class="text-xs font-black text-blue-500 hover:text-blue-700 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Baru
                            </button>
                        </div>

                        @if($addresses->isEmpty())
                        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                            <p class="text-sm font-black text-amber-700">Belum ada alamat tersimpan</p>
                            <button type="button" onclick="openAddressModal()"
                                class="mt-2 text-xs font-black text-blue-500 hover:underline">
                                + Tambah alamat sekarang
                            </button>
                        </div>
                        @else
                        <div class="flex flex-col gap-3" id="address-list">
                            @foreach($addresses as $addr)
                            <label class="address-option flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all
                                {{ $selectedAddress && $selectedAddress->address_id == $addr->address_id ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 hover:border-slate-200' }}">
                                <input type="radio" name="address_id_display" value="{{ $addr->address_id }}"
                                    class="mt-0.5 accent-indigo-600"
                                    {{ $selectedAddress && $selectedAddress->address_id == $addr->address_id ? 'checked' : '' }}
                                    onchange="selectAddress({{ $addr->address_id }}, '{{ $addr->province_id }}')">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-slate-600 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $addr->label }}</span>
                                        @if($addr->is_default)
                                        <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">Utama</span>
                                        @endif
                                    </div>
                                    <p class="font-black text-slate-900 text-sm">{{ $addr->nama_penerima }}</p>
                                    <p class="text-xs text-blue-600 font-bold mt-0.5">{{ $addr->no_telp }}</p>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $addr->alamat_lengkap }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif

                        {{-- Zona & berat --}}
                        @if($zone)
                        <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 rounded-xl border border-blue-100">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="text-xs font-black text-blue-600">{{ $zone->nama_zona }}</span>
                            <span class="text-xs text-blue-400">·</span>
                            <span class="text-xs text-blue-500">{{ number_format($beratGram, 0, ',', '.') }} gram</span>
                        </div>
                        @endif
                    </div>

                    {{-- Catatan untuk Penjual --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Catatan Pesanan</h2>
                        <p class="text-xs text-slate-400 mb-4">Instruksi khusus untuk penjual, contoh: "Packing rapi ya, buat hadiah 🎁"</p>
                        <textarea id="field-catatan" rows="3" maxlength="300"
                            placeholder="Contoh: Tolong packing yang rapi ya."
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none transition-all"></textarea>
                        <p class="text-right text-[10px] text-slate-300 mt-1"><span id="catatan-count">0</span>/300</p>
                    </div>

                    {{-- Pilih Kecepatan --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Pilih Kecepatan Pengiriman</h2>
                        <div class="flex flex-col gap-3" id="shipping-options">
                            @foreach ($opsiOngkir as $opsi)
                            <label class="shipping-option-label flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all
                                {{ $selectedOptionId == $opsi['option_id'] ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 hover:border-slate-200' }}"
                                data-option-id="{{ $opsi['option_id'] }}"
                                data-ongkir="{{ $opsi['ongkir'] }}">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_option_display"
                                        value="{{ $opsi['option_id'] }}"
                                        class="accent-indigo-600"
                                        {{ $selectedOptionId == $opsi['option_id'] ? 'checked' : '' }}>
                                    <div>
                                        <p class="font-black text-slate-900 text-sm">{{ $opsi['label'] }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            Estimasi tiba
                                            <span class="font-bold text-slate-600">{{ $opsi['tanggal_tiba'] }}</span>
                                            <span class="text-slate-400">({{ $opsi['estimasi_label'] }})</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-slate-900 text-sm">Rp {{ number_format($opsi['ongkir'], 0, ',', '.') }}</p>
                                    @if ($opsi['persen_tambahan'] > 0)
                                    <p class="text-[10px] text-indigo-500 font-bold mt-0.5">+{{ $opsi['persen_tambahan'] }}% lebih cepat</p>
                                    @else
                                    <p class="text-[10px] text-emerald-500 font-bold mt-0.5">Harga normal</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- KANAN --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-8">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Rincian Biaya</h2>

                        <div class="flex flex-col gap-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total Produk</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($totalProduk, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Ongkos Kirim</span>
                                <span class="font-bold text-slate-900" id="display-ongkir">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Biaya Layanan</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-slate-100 pt-3 flex justify-between">
                                <span class="font-black text-slate-900">Total</span>
                                <span class="font-black text-indigo-600 text-lg" id="display-total">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @isset($snapToken)
                        <button id="pay-button"
                            class="w-full mt-6 bg-slate-900 hover:bg-indigo-600 text-white py-4 rounded-2xl font-black text-sm transition-all transform active:scale-95 uppercase tracking-wider shadow-xl shadow-slate-200">
                            Bayar Sekarang
                        </button>
                        @else
                        <form action="{{ route('checkout') }}" method="POST" class="mt-6" id="checkout-form">
                            @csrf
                            <input type="hidden" name="shipping_option_id" id="hidden-shipping-option" value="{{ $selectedOptionId }}">
                            <input type="hidden" name="address_id" id="hidden-address-id" value="{{ $selectedAddress?->address_id }}">
                            <input type="hidden" name="catatan" id="hidden-catatan" value="">

                            @if ($request->has('product_id'))
                            <input type="hidden" name="product_id" value="{{ $request->product_id }}">
                            <input type="hidden" name="variant_id" value="{{ $request->variant_id }}">
                            <input type="hidden" name="quantity" value="{{ $request->quantity }}">
                            @else
                            @foreach ($request->input('selected_items', []) as $itemId)
                            <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                            @endforeach
                            @endif

                            <button type="submit"
                                class="w-full bg-slate-900 hover:bg-indigo-600 text-white py-4 rounded-2xl font-black text-sm transition-all transform active:scale-95 uppercase tracking-wider shadow-xl shadow-slate-200">
                                Bayar Sekarang
                            </button>
                        </form>
                        @endisset

                        <p class="text-center mt-4 text-[10px] text-slate-400 font-medium">🛡️ Transaksi aman & terverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('profile._address_modal')

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        const totalProduk = {{ $totalProduk }};
        const biayaAdmin  = {{ $biayaAdmin }};

        // Pilih opsi pengiriman → update harga live
        document.querySelectorAll('.shipping-option-label').forEach(function (label) {
            label.addEventListener('click', function () {
                const optionId = this.dataset.optionId;
                const ongkir   = parseInt(this.dataset.ongkir);

                document.getElementById('hidden-shipping-option').value = optionId;

                const total = totalProduk + ongkir + biayaAdmin;
                document.getElementById('display-ongkir').textContent = 'Rp ' + formatRupiah(ongkir);
                document.getElementById('display-total').textContent  = 'Rp ' + formatRupiah(total);

                document.querySelectorAll('.shipping-option-label').forEach(l => {
                    l.classList.remove('border-indigo-500', 'bg-indigo-50');
                    l.classList.add('border-slate-100');
                });
                this.classList.remove('border-slate-100');
                this.classList.add('border-indigo-500', 'bg-indigo-50');
            });
        });

        // Pilih alamat → reload halaman dengan address_id baru
        function selectAddress(addressId, provinceId) {
            document.getElementById('hidden-address-id').value = addressId;

            // Highlight border
            document.querySelectorAll('.address-option').forEach(el => {
                el.classList.remove('border-indigo-500', 'bg-indigo-50');
                el.classList.add('border-slate-100');
            });
            event.currentTarget.closest('.address-option').classList.remove('border-slate-100');
            event.currentTarget.closest('.address-option').classList.add('border-indigo-500', 'bg-indigo-50');

            // Reload untuk recalculate zona & ongkir
            const url = new URL(window.location.href);
            url.searchParams.set('address_id', addressId);
            window.location.href = url.toString();
        }

        function formatRupiah(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Catatan counter & sync ke hidden input
        const catatanEl = document.getElementById('field-catatan');
        const catatanCount = document.getElementById('catatan-count');
        const hiddenCatatan = document.getElementById('hidden-catatan');
        if (catatanEl) {
            catatanEl.addEventListener('input', function () {
                catatanCount.textContent = this.value.length;
                hiddenCatatan.value = this.value;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            @isset($snapToken)
            const snapToken = "{{ $snapToken }}";
            function bayar() {
                window.snap.pay(snapToken, {
                    onSuccess: function () { window.location.href = "{{ route('orders.history') }}"; },
                    onPending: function () { window.location.href = "{{ route('orders.history') }}"; },
                    onError:   function () { Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', customClass: { popup: 'rounded-[2rem]' } }); },
                    onClose:   function () { Swal.fire({ icon: 'info', title: 'Pembayaran Ditutup', confirmButtonText: 'Oke', customClass: { popup: 'rounded-[2rem]' } }); }
                });
            }
            bayar();
            const btn = document.getElementById('pay-button');
            if (btn) btn.addEventListener('click', bayar);
            @endisset

            @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", customClass: { popup: 'rounded-[2rem]' } });
            @endif
        });
    </script>
</x-app-layout>
