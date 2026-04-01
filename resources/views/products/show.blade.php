<x-app-layout>
    @php
        $user = auth()->user();
        $store = $product->store;
        $estimasi = null;

        if ($user && $store && $user->province_id && $store->province_id) {
            $shippingService = app(\App\Services\ShippingService::class);
            try {
                $zone = $shippingService->tentukanZona(
                    (string) $store->province_id,
                    (string) $user->province_id
                );
                // Ambil opsi reguler (kurang_hari = 0) untuk estimasi default
                $opsiReguler = \App\Models\ShippingOption::where('kurang_hari', 0)->first();
                if ($opsiReguler) {
                    $estimasi = $shippingService->formatEstimasi($zone, $opsiReguler);
                }
            } catch (\Exception $e) {
                $estimasi = null;
            }
        }

        $hasVariants = $product->variants->count() > 0;
    @endphp

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===== PRODUK DETAIL ===== --}}
    <div x-data="{
            qty: 1,
            selectedVariant: '',
            maxStock: {{ $hasVariants ? 0 : $product->stok }},
            price: {{ $hasVariants ? 0 : $product->harga }},
            variantSelected: {{ $hasVariants ? 'false' : 'true' }}
        }" class="py-12 bg-slate-50/50">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Navigation --}}
            <div class="mb-8 flex items-center justify-between">
                <a href="{{ route('dashboard') }}"
                    class="group inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-all">
                    <div class="p-2 bg-white rounded-xl shadow-sm mr-3 group-hover:bg-indigo-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    Kembali Belanja
                </a>
            </div>

            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-white">
                <div class="flex flex-col lg:flex-row items-stretch">

                    {{-- KIRI: Galeri Foto --}}
                    <div class="lg:w-1/2 p-4 lg:p-12 bg-white">
                        <div class="sticky top-8">
                            <div
                                class="relative aspect-square rounded-[2.5rem] overflow-hidden bg-slate-100 group shadow-inner">
                                @php $primary = $product->primaryImage ?? $product->images->first(); @endphp
                                <img src="{{ asset('storage/' . ($primary->gambar ?? '')) }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    id="mainImage" onerror="this.src='https://placehold.co/800x800?text=No+Image'">
                                <div class="absolute top-6 left-6">
                                    <span
                                        class="px-4 py-2 bg-white/90 backdrop-blur text-indigo-600 rounded-2xl text-xs font-black uppercase tracking-widest shadow-sm">
                                        {{ $product->category->nama_kategori }}
                                    </span>
                                </div>
                            </div>

                            @if ($product->images->count() > 1)
                                <div class="flex mt-6 gap-3 overflow-x-auto pb-4 pt-2 no-scrollbar">
                                    @foreach ($product->images as $img)
                                        <div class="relative flex-shrink-0 group">
                                            <img src="{{ asset('storage/' . $img->gambar) }}"
                                                onclick="document.getElementById('mainImage').src=this.src"
                                                class="w-20 h-20 object-cover rounded-2xl cursor-pointer border-2 border-transparent hover:border-indigo-500 transition-all duration-300">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- KANAN: Form Pembelian --}}
                    <div
                        class="lg:w-1/2 p-8 lg:p-12 flex flex-col bg-slate-50/30 border-l border-slate-100 h-full min-h-full">
                        <form id="purchaseForm" action="{{ route('checkout.review') }}" method="GET"
                            class="flex flex-col flex-grow">
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                            <div class="flex-grow">
                                <h1
                                    class="text-4xl font-black text-slate-900 mb-4 leading-tight tracking-tighter italic">
                                    {{ $product->nama_produk }}
                                </h1>

                                <div class="flex items-center gap-4 mb-6">
                                    <p class="text-3xl font-black text-indigo-600">
                                        <span x-show="variantSelected"
                                            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(price)"></span>
                                        <span x-show="!variantSelected" class="text-slate-400">Pilih Varian...</span>
                                    </p>
                                    <template x-if="variantSelected">
                                        <span
                                            class="px-3 py-1 bg-white text-slate-500 border border-slate-200 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                                            Stok: <span x-text="maxStock"></span>
                                        </span>
                                    </template>
                                </div>

                                <div class="flex flex-col gap-6 mb-8 border-y border-slate-100 py-6">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pengiriman</span>
                                        @if($estimasi)
    <span class="text-xs font-black text-emerald-600 uppercase italic">
        Estimasi {{ $estimasi }}
    </span>
@else
    <span class="text-xs font-black text-slate-400 italic">
        Login untuk lihat estimasi
    </span>
@endif
                                    </div>

                                    @if ($hasVariants)
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilihan</span>
                                            <span class="text-xs font-black text-indigo-600 uppercase italic"
                                                x-text="selectedVariant || 'Belum dipilih'"></span>
                                        </div>

                                        <div class="flex flex-col gap-3">
                                            <p class="text-[10px] font-bold text-slate-900 uppercase tracking-widest">Pilih
                                                Varian Produk</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($product->variants as $variant)
                                                                                    <label
                                                                                        class="relative {{ $variant->stok == 0 ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                                                                        <input type="radio" name="variant_id" value="{{ $variant->variant_id }}"
                                                                                            class="peer hidden" {{ $variant->stok == 0 ? 'disabled' : '' }}
                                                                                            required @click="price = {{ $product->harga + $variant->harga_tambahan }};
                                                                                                    maxStock = {{ $variant->stok }};
                                                                                                    selectedVariant = '{{ $variant->nama_varian }}';
                                                                                                    variantSelected = true;
                                                                                                    if(qty > maxStock) qty = maxStock;">
                                                                                        <div
                                                                                            class="px-4 py-3 border-2 rounded-xl transition-all flex items-center gap-3 whitespace-nowrap
                                                                                                {{ $variant->stok == 0
                                                    ? 'bg-slate-50 border-slate-100 cursor-not-allowed opacity-50'
                                                    : 'bg-white border-slate-100 hover:border-slate-300 cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600' }}">
                                                                                            <span
                                                                                                class="text-xs font-bold uppercase tracking-tight">{{ $variant->nama_varian }}</span>
                                                                                            @if ($variant->stok == 0)
                                                                                                <span class="text-[10px] font-bold text-red-400">Habis</span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-6 mb-8" x-show="variantSelected" x-transition>
                                    <div
                                        class="flex items-center gap-4 bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm">
                                        <button type="button" @click="if(qty > 1) qty--"
                                            class="text-slate-400 hover:text-indigo-600 font-bold text-lg">-</button>
                                        <input type="number" name="quantity" x-model="qty" readonly
                                            class="w-10 text-center bg-transparent border-none font-black text-base focus:ring-0 p-0">
                                        <button type="button" @click="if(qty < maxStock) qty++"
                                            class="text-slate-400 hover:text-indigo-600 font-bold text-lg">+</button>
                                    </div>
                                    <p class="text-slate-400 text-[11px] leading-relaxed italic">
                                        *Total biaya dan ongkir akan dihitung pada halaman berikutnya.
                                    </p>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-slate-100 mt-auto">
                                <div class="flex gap-3" x-show="variantSelected"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4">
                                    <button type="button" onclick="submitCart()"
                                        class="flex-1 bg-white border-2 border-slate-900 text-slate-900 hover:bg-slate-50 py-4 rounded-2xl font-bold text-sm transition-all transform active:scale-95 uppercase">
                                        + Keranjang
                                    </button>
                                    <button type="submit"
                                        class="flex-[1.5] bg-slate-900 hover:bg-indigo-600 text-white py-4 rounded-2xl font-bold text-sm shadow-xl shadow-slate-200 transition-all transform active:scale-95 uppercase">
                                        Beli Sekarang
                                    </button>
                                </div>

                                <div x-show="!variantSelected"
                                    class="bg-slate-100 text-slate-400 py-4 rounded-2xl text-center font-bold text-xs uppercase tracking-widest">
                                    Silakan pilih varian terlebih dahulu
                                </div>

                                <p class="text-center mt-6 text-[10px] text-slate-400 font-medium">🛡️ Transaksi aman &
                                    terverifikasi oleh MampangPedia</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- end x-data produk --}}

    {{-- ===== SECTION REVIEW — di luar x-data ===== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @include('products._review_section', [
            'product' => $product,
            'reviews' => $reviews,
            'eligibleOrders' => $eligibleOrders,
        ])
    </div>


       
           
           

           
       
    <style>

           
       
        input::-webkit-
           outer-spin-button,

           
               input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success', title: 'Berhasil!',
            text: "{{ session('success') }}",
                showConfirmButton: false, timer: 2000,
                customClass: { popup: 'rounded-[2rem]' }
            });

               </script>
@endif

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('snap_token'))
            window.snap.pay("{{ session('snap_token') }}", {
                onSuccess: function (result) { window.location.href = "{{ route('orders.history') }}"; },
                    onPending: function (result) { window.location.href = "{{ route('orders.history') }}"; },
                onError: function (result) { handleCancel(); },
                onClose: function () { handleCancel(); }
            });
            function handleCancel() {
                    fetch("{{ route('orders.cancel', session('order_id')) }}", {
                        method: 'GET',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.json())
                    .then(() => {
                        Swal.fire({
                            icon: 'info', title: 'Pesanan Dibatalkan',
                        text: 'Lu nutup pembayaran, jadi stok kita balikin ya!',
                            confirmButtonText: 'Oke Cu'
                        }).then(() => window.location.reload());
                    });
                }
        @endif
        });
    </script>

    <form id="cartForm" action="{{ route('cart.add') }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="product_id" id="cartProductId">
        <input type="hidden" name="variant_id" id="cartVariantId">
        <input type="hidden" name="quantity" id="cartQty">
    </form>

    <script>
        function submitCart() {
            const mainForm = document.getElementById('purchaseForm');
            document.getElementById('cartProductId').value = mainForm.querySelector('[name=product_id]').value;
            const variantInput = mainForm.querySelector('[name=variant_id]:checked');
            document.getElementById('cartVariantId').value = variantInput ? variantInput.value : '';
            document.getElementById('cartQty').value = mainForm.querySelector('[name=quantity]').value;
            document.getElementById('cartForm').submit();
        }
    </script>
</x-app-layout>