<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        {{-- HEADER --}}
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('kurir.index') }}"
                        class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500">Detail Paket</p>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Order #{{ $order->order_id }}</h1>
                    </div>
                    <div class="ml-auto">
                        @if($order->nomor_resi)
                            <button onclick="cetakResi()"
                                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-black rounded-2xl hover:bg-blue-700 transition-all shadow-md shadow-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Resi
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-5">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl font-semibold text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl font-semibold text-sm">
                    ✕ {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl font-semibold text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- KIRI --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- STATUS & RESI --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Status Pengiriman</p>
                        <div class="flex items-center gap-3 flex-wrap">
                            @php
                                $statusConfig = [
                                    'dibayar' => ['bg-violet-100 text-violet-700', 'Menunggu Diambil Kurir'],
                                    'dikemas' => ['bg-blue-100 text-blue-700',   'Proses Pengemasan'],
                                    'dikirim' => ['bg-amber-100 text-amber-700', 'Sedang Dikirim'],
                                    'selesai' => ['bg-green-100 text-green-700', 'Selesai'],
                                ];
                                [$statusClass, $statusLabel] = $statusConfig[$order->status] ?? ['bg-slate-100 text-slate-700', $order->status];
                            @endphp
                            <span class="px-4 py-2 rounded-xl text-sm font-black {{ $statusClass }}">{{ $statusLabel }}</span>
                            @if($order->nomor_resi)
                                <span class="text-sm text-slate-500 font-semibold">
                                    Resi: <span class="font-black text-slate-800 font-mono">{{ $order->nomor_resi }}</span>
                                </span>
                            @endif
                        </div>

                        @if($order->estimasi_tiba)
                            <div class="mt-4 flex items-center gap-2 p-3 bg-indigo-50 rounded-2xl">
                                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    @if($order->shippingOption)
                                        <p class="text-xs font-black text-indigo-700">{{ $order->shippingOption->label }}</p>
                                    @endif
                                    @if($order->estimasi_tiba_max && $order->estimasi_tiba->ne($order->estimasi_tiba_max))
                                        {{ $order->estimasi_tiba->format('d') }} -
                                        {{ $order->estimasi_tiba_max->translatedFormat('d M Y') }}
                                    @else
                                        {{ $order->estimasi_tiba->translatedFormat('d M Y') }}
                                    @endif
                                </div>
                                @php $sisaHari = now()->diffInDays($order->estimasi_tiba, false); @endphp
                                <div class="ml-auto text-right">
                                    <span class="text-xs font-black px-2 py-1 rounded-lg
                                        {{ $sisaHari <= 0 ? 'bg-red-100 text-red-600' : ($sisaHari == 1 ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600') }}">
                                        {{ $sisaHari <= 0 ? 'Terlambat!' : $sisaHari . ' hari lagi' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ALAMAT TUJUAN --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Alamat Tujuan</p>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-black text-slate-900">{{ $order->nama_penerima ?? $order->user->name }}</p>
                                <p class="text-sm text-blue-600 font-bold mt-0.5">{{ $order->no_telp_penerima ?? $order->user->no_telp }}</p>
                                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                    {{ $order->shipping_address ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ISI PAKET --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Isi Paket</p>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-4">
                                    @if($item->product->primaryImage)
                                        <img src="{{ Storage::url($item->product->primaryImage->gambar) }}"
                                            class="w-14 h-14 rounded-2xl object-cover bg-slate-100 shrink-0">
                                    @else
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 shrink-0 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-black text-slate-800 text-sm">{{ $item->product->nama_produk }}</p>
                                        @if($item->variant)
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $item->variant->nama_varian }}</p>
                                        @endif
                                        <p class="text-xs text-slate-400">{{ $item->jumlah }}x · Berat: {{ $item->variant?->berat ?? '-' }}gr</p>
                                    </div>
                                    <p class="text-sm font-black text-slate-800">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($order->status === 'selesai' && $order->foto_konfirmasi)
                        <div class="bg-white rounded-3xl border border-emerald-100 p-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Foto Konfirmasi</p>
                            <img src="{{ Storage::url($order->foto_konfirmasi) }}"
                                class="w-full rounded-2xl object-cover max-h-64" alt="Foto konfirmasi pengiriman">
                        </div>
                    @endif

                </div>

                {{-- KANAN --}}
                <div class="space-y-5">

                    <div class="bg-white rounded-3xl border border-slate-100 p-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Ringkasan</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Subtotal</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($order->total_harga - $order->ongkir - 2500, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Ongkir</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Biaya Layanan</span>
                                <span class="font-bold text-slate-800">Rp 2.500</span>
                            </div>
                            <hr class="border-slate-100 my-1">
                            <div class="flex justify-between">
                                <span class="font-black text-slate-800">Total</span>
                                <span class="font-black text-blue-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @if($order->status === 'selesai')
                            <div class="mt-4 p-3 bg-emerald-50 rounded-2xl flex items-center justify-between">
                                <p class="text-xs font-black text-emerald-600">Penghasilan kamu</p>
                                <p class="text-sm font-black text-emerald-700">+Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
                            </div>
                        @else
                            <div class="mt-4 p-3 bg-violet-50 rounded-2xl flex items-center justify-between">
                                <p class="text-xs font-black text-violet-600">Ongkir kamu</p>
                                <p class="text-sm font-black text-violet-700">+Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($order->status === 'dibayar' && is_null($order->kurir_id))
                        <div class="bg-white rounded-3xl border border-violet-100 p-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Ambil Paket Ini</p>
                            <p class="text-xs text-slate-400 mb-4">Setelah kamu ambil, paket ini jadi tanggung jawab kamu.</p>
                            <form action="{{ route('kurir.orders.take', $order->order_id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Ambil paket #{{ $order->order_id }}?')"
                                    class="w-full px-4 py-3.5 bg-violet-600 text-white text-sm font-black rounded-2xl hover:bg-violet-700 transition-all shadow-md shadow-violet-100">
                                    🚀 Ambil Paket Ini
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($order->status === 'dikemas' && $order->kurir_id === Auth::id())
                        <form action="{{ route('kurir.orders.update-status', $order->order_id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="dikirim">
                            <button type="submit" onclick="return confirm('Paket sudah dikemas dan siap dikirim?')"
                                class="w-full px-4 py-3.5 bg-blue-600 text-white text-sm font-black rounded-2xl hover:bg-blue-700 transition-all shadow-md shadow-blue-100">
                                📦 Mulai Kirim
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'dikirim' && $order->kurir_id === Auth::id())
                        <div class="bg-white rounded-3xl border border-emerald-100 p-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Konfirmasi Diterima</p>
                            <p class="text-xs text-slate-400 mb-4">Upload foto bukti paket sudah diterima pembeli.</p>
                            <form action="{{ route('kurir.orders.update-status', $order->order_id) }}" method="POST"
                                enctype="multipart/form-data" id="form-konfirmasi">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="selesai">
                                <div class="mb-4">
                                    <div id="foto-preview-wrapper" class="hidden mb-3">
                                        <img id="foto-preview" src="" alt="Preview"
                                            class="w-full h-48 object-cover rounded-2xl bg-slate-100">
                                    </div>
                                    <label for="foto-input"
                                        class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-blue-300 hover:bg-blue-50/30 transition-all"
                                        id="foto-label">
                                        <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p class="text-xs font-bold text-slate-400">Klik untuk pilih foto</p>
                                        <p class="text-[10px] text-slate-300 mt-1">JPG, PNG · Maks 3MB</p>
                                        <input type="file" id="foto-input" name="foto_konfirmasi"
                                            accept="image/jpeg,image/png" class="hidden">
                                    </label>
                                </div>
                                <button type="submit" id="btn-konfirmasi"
                                    onclick="return confirm('Pastikan foto sudah benar. Konfirmasi paket sudah diterima?')"
                                    class="w-full px-4 py-3.5 bg-emerald-600 text-white text-sm font-black rounded-2xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-100 disabled:opacity-40 disabled:cursor-not-allowed"
                                    disabled>
                                    ✓ Konfirmasi Diterima
                                </button>
                                <p class="text-[10px] text-slate-400 text-center mt-2">
                                    Ongkir <span class="font-black text-emerald-600">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span> akan masuk ke saldo kamu
                                </p>
                            </form>
                        </div>
                    @endif

                    @if($order->nomor_resi)
                        <button onclick="cetakResi()"
                            class="w-full px-4 py-3.5 bg-slate-100 text-slate-700 text-sm font-black rounded-2xl hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak Resi
                        </button>
                    @endif

                    <a href="{{ route('kurir.saldo') }}"
                        class="w-full px-4 py-3.5 bg-indigo-50 text-indigo-700 text-sm font-black rounded-2xl hover:bg-indigo-100 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Lihat Saldo Saya
                    </a>

                </div>
            </div>
        </div>
    </div>

    {{-- TEMPLATE RESI --}}
    <div id="resi-print" class="hidden">
        <div style="font-family: monospace; width: 300px; padding: 16px; border: 2px solid #000;">
            <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;">
                <div style="font-size: 20px; font-weight: 900; letter-spacing: -1px;">MampangPedia</div>
                <div style="font-size: 10px; color: #555;">RESI PENGIRIMAN</div>
            </div>
            <div style="font-size: 11px; margin-bottom: 8px;">
                <div style="font-weight: bold; color: #555; text-transform: uppercase; font-size: 9px;">No. Resi</div>
                <div style="font-size: 14px; font-weight: 900; letter-spacing: 1px;">{{ $order->nomor_resi }}</div>
            </div>
            <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 8px 0; margin: 8px 0;">
                <div style="font-size: 9px; font-weight: bold; color: #555; text-transform: uppercase; margin-bottom: 4px;">Pengirim</div>
                <div style="font-weight: bold;">{{ $order->nama_kurir }}</div>
                <div style="font-size: 10px; color: #333;">MampangPedia Store</div>
            </div>
            <div style="padding: 8px 0; margin-bottom: 8px;">
                <div style="font-size: 9px; font-weight: bold; color: #555; text-transform: uppercase; margin-bottom: 4px;">Penerima</div>
                <div style="font-weight: bold;">{{ $order->nama_penerima ?? $order->user->name }}</div>
                <div style="font-size: 10px; color: #333;">{{ $order->no_telp_penerima ?? $order->user->no_telp }}</div>
                <div style="font-size: 10px; color: #333; margin-top: 4px; line-height: 1.5;">
                    {{ $order->shipping_address ?? '-' }}
                </div>
            </div>
            <div style="border-top: 1px dashed #000; padding-top: 8px; font-size: 10px;">
                <div style="display: flex; justify-content: space-between;">
                    <span>Order #{{ $order->order_id }}</span>
                    <span>{{ now()->format('d/m/Y H:i') }}</span>
                </div>
                @if($order->shippingOption)
                    <div style="margin-top: 2px; font-size: 9px; color: #555;">{{ $order->shippingOption->label }}</div>
                @endif
                <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                    <span>Total</span>
                    <span style="font-weight: bold;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('foto-input')?.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('foto-preview').src = e.target.result;
                document.getElementById('foto-preview-wrapper').classList.remove('hidden');
                document.getElementById('foto-label').classList.add('hidden');
                document.getElementById('btn-konfirmasi').disabled = false;
            };
            reader.readAsDataURL(file);
        });

        function cetakResi() {
            const resiContent = document.getElementById('resi-print').innerHTML;
            const printWindow = window.open('', '_blank', 'width=400,height=600');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Resi #{{ $order->nomor_resi }}</title>
                        <style>body { margin: 20px; background: white; } @media print { body { margin: 0; } }</style>
                    </head>
                    <body>
                        ${resiContent}
                        <script>window.onload = () => { window.print(); window.close(); }<\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</x-app-layout>
