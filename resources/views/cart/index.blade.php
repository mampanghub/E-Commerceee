<x-app-layout>
    <div class="py-12 bg-[#F8FAFC] min-h-screen" x-data="cartHandler()">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- NOTIFIKASI FLASH MESSAGE --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="flex items-end justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Keranjang Belanja</h2>
                    <p class="text-slate-500 text-sm mt-1">Kamu punya <span
                            class="font-bold text-blue-600">{{ $items->count() }} jenis produk</span>.</p>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="text-sm font-bold text-blue-600 hover:text-indigo-700 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Lanjut Belanja
                </a>
            </div>

            @if ($items->count() > 0)
                <form id="checkout-form" action="{{ route('checkout') }}" method="POST">@csrf</form>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                    <div class="lg:col-span-8 space-y-6">
                        @foreach ($items as $item)
                            @php
                                $hargaSatuan = $item->product->harga + ($item->variant->harga_tambahan ?? 0);
                                $stokMaksimal = $item->variant ? $item->variant->stok : $item->product->stok;
                                $fotoVarian = $item->product->images->where('variant_id', $item->variant_id)->first();
                                $gambar =
                                    $fotoVarian && !empty($fotoVarian->gambar)
                                        ? asset('storage/' . $fotoVarian->gambar)
                                        : ($item->product->primaryImage
                                            ? asset('storage/' . $item->product->primaryImage->gambar)
                                            : 'https://placehold.co/300x300?text=No+Image');
                            @endphp

                            <div
                                class="group bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center gap-6 relative hover:shadow-md transition-shadow">
                                <div class="absolute top-6 left-6 z-10 sm:relative sm:top-0 sm:left-0">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->cart_item_id }}"
                                        form="checkout-form"
                                        class="w-6 h-6 rounded-lg border-slate-200 text-blue-600 cursor-pointer focus:ring-blue-500"
                                        checked @change="updateTotal" data-name="{{ $item->product->nama_produk }}"
                                        data-price="{{ $hargaSatuan }}"
                                        :data-qty="getItemQty({{ $item->cart_item_id }}, {{ $item->jumlah }})"
                                        data-store-province="{{ $item->product->store->province_id }}">
                                </div>

                                <div
                                    class="w-32 h-32 rounded-2xl overflow-hidden bg-slate-50 flex-shrink-0 border border-slate-50">
                                    <img src="{{ $gambar }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>

                                <div class="flex-grow text-center sm:text-left">
                                    <h3 class="font-bold text-slate-800 text-lg leading-tight">
                                        {{ $item->product->nama_produk }}</h3>
                                    <p class="text-sm text-blue-500 font-bold mt-1">
                                        {{ $item->variant ? $item->variant->warna . ' - ' . $item->variant->ukuran : 'Default' }}
                                    </p>
                                    <p class="text-slate-900 font-black text-xl mt-2">
                                        Rp{{ number_format($hargaSatuan, 0, ',', '.') }}</p>
                                </div>

                                <div class="flex flex-row sm:flex-col items-center gap-4">
                                    <div
                                        class="flex items-center gap-2 bg-slate-50 rounded-2xl p-1 border border-slate-100">
                                        {{-- Tombol Kurang --}}
                                        <button type="button"
                                            @click="updateQty({{ $item->cart_item_id }}, -1, {{ $stokMaksimal }})"
                                            :disabled="getItemQty({{ $item->cart_item_id }}, {{ $item->jumlah }}) <= 1"
                                            class="w-8 h-8 flex items-center justify-center bg-white rounded-xl shadow-sm text-slate-600 hover:text-rose-500 disabled:opacity-30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>

                                        <span class="font-black px-2 text-slate-700"
                                            x-text="getItemQty({{ $item->cart_item_id }}, {{ $item->jumlah }})"></span>

                                        {{-- Tombol Tambah --}}
                                        <button type="button"
                                            @click="updateQty({{ $item->cart_item_id }}, 1, {{ $stokMaksimal }})"
                                            :disabled="getItemQty({{ $item->cart_item_id }}, {{ $item->jumlah }}) >=
                                                {{ $stokMaksimal }}"
                                            class="w-8 h-8 flex items-center justify-center bg-white rounded-xl shadow-sm text-slate-600 hover:text-blue-600 disabled:opacity-30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST"
                                        class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus barang ini?')"
                                            class="p-3 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Ringkasan --}}
                    <div class="lg:col-span-4 sticky top-10">
                        <div
                            class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                            <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-50 pb-4">Ringkasan
                                Pesanan</h3>

                            {{-- Daftar produk yang dicentang --}}
                            <div class="flex flex-col gap-3 mb-6" id="selected-summary">
                                @foreach ($items as $item)
                                    @php $hargaSatuan = $item->product->harga + ($item->variant->harga_tambahan ?? 0); @endphp
                                    <div class="selected-item-row hidden bg-slate-50 rounded-2xl p-3 text-xs"
                                        data-item-id="{{ $item->cart_item_id }}">
                                        <p class="font-black text-slate-800 truncate">{{ $item->product->nama_produk }}
                                        </p>
                                        @if ($item->variant)
                                            <p class="text-indigo-500 font-bold">{{ $item->variant->nama_varian }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-4 border-t-2 border-dashed border-slate-100 flex flex-col gap-1 mb-8">
                                <span class="text-xs text-slate-400 font-bold uppercase">Total & Ongkir</span>
                                <span class="text-sm text-slate-400 italic">Dihitung di halaman berikutnya</span>
                            </div>

                            <button type="button" @click="keHalamanReview()" :disabled="jumlahDipilih == 0"
                                class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-blue-600 disabled:bg-slate-300 transition-all flex items-center justify-center gap-3">
                                Checkout
                            </button>
                        </div>
                    </div>
                </div>
            @else
                {{-- Tampilan Kosong (Empty State) --}}
                <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                    {{-- Ilustrasi --}}
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-blue-100 rounded-full blur-3xl opacity-50 scale-150"></div>
                        <div class="relative bg-white p-8 rounded-[3rem] shadow-xl shadow-blue-100/50">
                            <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l2 2 4-4" class="opacity-0 group-hover:opacity-100 transition-opacity" />
                            </svg>
                        </div>
                    </div>

                    {{-- Pesan --}}
                    <h3 class="text-2xl font-black text-slate-900 mb-3">Keranjangnya Masih Sepi, Cu!</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mb-10 leading-relaxed">
                        Sepertinya kamu belum nambahin barang apa-apa. Yuk, cari produk keren buat ngisi keranjangmu!
                    </p>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('dashboard') }}"
                            class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-blue-600 transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Mulai Belanja
                        </a>
                        <a href="{{ route('orders.history') }}"
                            class="px-8 py-4 bg-white text-slate-600 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                            Lihat Pesanan Saya
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        // PERBAIKAN: Fungsi cancelOrder harus di luar cartHandler() agar bisa diakses global
        async function cancelOrder(orderId) {
            try {
                // Gunakan path statis, jangan pakai route() helper
                const response = await fetch("/orders/" + orderId + "/cancel", {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                // Cek apakah response adalah JSON
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const data = await response.json();
                    if (data.success) {
                        console.log('Pesanan berhasil dibatalkan:', data.message);
                        // PERBAIKAN: Redirect ke halaman keranjang, bukan history
                        window.location.href = "{{ route('cart.index') }}";
                    } else {
                        console.log('Gagal membatalkan pesanan:', data.message);
                        window.location.href = "{{ route('cart.index') }}";
                    }
                } else {
                    // Jika bukan JSON, redirect ke keranjang
                    window.location.href = "{{ route('cart.index') }}";
                }
            } catch (error) {
                console.error('Error saat membatalkan pesanan:', error);
                // Redirect ke keranjang jika error
                window.location.href = "{{ route('cart.index') }}";
            }
        }

        function cartHandler() {
            return {
                jumlahDipilih: 0,
                userProvince: {{ Auth::user()->province_id ?? 0 }},
                itemsQty: {
                    @foreach ($items as $item)
                        {{ $item->cart_item_id }}: {{ $item->jumlah }},
                    @endforeach
                },

                async prosesPembayaran() {
                    const checkedBoxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
                    if (checkedBoxes.length === 0) {
                        alert('Pilih minimal satu barang dulu, Cu!');
                        return;
                    }

                    try {
                        const formData = new FormData(document.getElementById('checkout-form'));

                        const response = await fetch("{{ route('checkout') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Simpan order_id agar bisa dibatalkan jika popup ditutup
                            const orderId = result.order_id;

                            window.snap.pay(result.snap_token, {
                                onSuccess: function(result) {
                                    window.location.href = "{{ route('orders.history') }}";
                                },
                                onPending: function(result) {
                                    window.location.href = "{{ route('orders.history') }}";
                                },
                                onError: function(result) {
                                    // Jika pembayaran gagal, batalkan pesanan
                                    cancelOrder(orderId);
                                },
                                onClose: function() {
                                    // Jika popup ditutup, batalkan pesanan
                                    cancelOrder(orderId);
                                }
                            });
                        } else {
                            alert(result.message || 'Gagal membuat pesanan');
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Terjadi kesalahan sistem saat menghubungi server.');
                    }
                },

                init() {
                    this.updateTotal();
                },

                keHalamanReview() {
                    const checkedBoxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
                    if (checkedBoxes.length === 0) {
                        alert('Pilih minimal satu barang dulu, Cu!');
                        return;
                    }
                    const form = document.getElementById('checkout-form');
                    form.action = "{{ route('checkout.review') }}";
                    form.method = "POST";
                    form.submit();
                },

                getItemQty(id, initialQty) {
                    // PERBAIKAN: Tambahkan 'undefined' yang hilang
                    if (this.itemsQty[id] === undefined) this.itemsQty[id] = initialQty;
                    return this.itemsQty[id];
                },

                async updateQty(itemId, change, maxStock) {
                    const currentQty = this.itemsQty[itemId];
                    const newQty = currentQty + change;

                    if (newQty < 1 || newQty > maxStock) return;

                    this.itemsQty[itemId] = newQty;

                    const cb = document.querySelector(`input[value="${itemId}"]`);
                    if (cb) cb.dataset.qty = newQty;

                    this.updateTotal();

                    try {
                        const response = await fetch(`/cart/update/${itemId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                jumlah: newQty
                            })
                        });
                        if (!response.ok) throw new Error();
                    } catch (e) {
                        this.itemsQty[itemId] = currentQty;
                        if (cb) cb.dataset.qty = currentQty;
                        this.updateTotal();
                        alert('Gagal update stok, Cu!');
                    }
                },

                updateTotal() {
                    const checkedBoxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
                    const checkedIds = Array.from(checkedBoxes).map(el => el.value);

                    // Tampilkan/sembunyikan baris produk di ringkasan
                    document.querySelectorAll('.selected-item-row').forEach(row => {
                        if (checkedIds.includes(row.dataset.itemId)) {
                            row.classList.remove('hidden');
                        } else {
                            row.classList.add('hidden');
                        }
                    });

                    this.jumlahDipilih = checkedIds.length;
                },

                get ongkir() {
                    return this.totalProduk > 0 ? this.currentOngkir : 0;
                },
                get biayaLayanan() {
                    return this.totalProduk > 0 ? this.baseLayanan : 0;
                },
                get totalAkhir() {
                    return this.totalProduk + this.ongkir + this.biayaLayanan;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number).replace('IDR', 'Rp');
                }
            }
        }

        @if (session('snap_token'))
            window.snap.pay("{{ session('snap_token') }}", {
                onSuccess: function(result) {
                    window.location.href = "{{ route('orders.history') }}";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('orders.history') }}";
                },
                onError: function(result) {
                    // Gunakan path statis juga di sini
                    window.location.href = "/orders/" + "{{ session('order_id') }}" + "/cancel";
                },
                onClose: function() {
                    // Gunakan path statis juga di sini
                    window.location.href = "/orders/" + "{{ session('order_id') }}" + "/cancel";
                }
            });
        @endif

        // Force reload jika halaman diakses via back button
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</x-app-layout>
