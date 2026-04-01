<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="fas fa-boxes text-indigo-600 mr-2"></i> {{ __('Manajemen Produk') }}
            </h2>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 shadow-md hover:shadow-lg transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produk Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div id="success-alert"
                class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm transition-all duration-1000 ease-in-out">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            <div class="mb-6 bg-white border-l-4 border-indigo-500 p-4 shadow-sm rounded-r-lg flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    Total produk aktif Anda: <span class="font-bold text-indigo-600">{{ $products->count() }}</span>
                </p>
                <span class="text-xs text-gray-400 italic">Terakhir diperbarui: {{ now()->format('d M Y') }}</span>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-10">No</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Varian & Stok</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Kategori</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Harga & Berat</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($products as $item)
                            <tr class="hover:bg-indigo-50/30 transition duration-200">
                                <td class="px-6 py-4 text-center text-sm text-gray-400">{{ $loop->iteration }}</td>

                                {{-- FOTO & NAMA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-16 w-16 flex-shrink-0">
                                            @php $displayImage = $item->images->first(); @endphp
                                            @if ($displayImage)
                                            <img src="{{ asset('storage/' . $displayImage->gambar) }}"
                                                class="h-16 w-16 rounded-xl object-cover border border-gray-200 shadow-sm"
                                                onerror="this.src='https://placehold.co/100x100?text=No+Image'">
                                            @else
                                            <div class="h-16 w-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 border border-dashed border-gray-300">
                                                <i class="far fa-image text-2xl"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="max-w-[200px]">
                                            <div class="text-sm font-bold text-gray-900 truncate" title="{{ $item->nama_produk }}">
                                                {{ $item->nama_produk }}
                                            </div>
                                            <div class="text-[11px] text-gray-500 mt-1">ID: #PROD-{{ $item->product_id }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- VARIAN --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach ($item->variants as $variant)
                                        <div class="inline-flex flex-col bg-white border border-gray-200 rounded-lg p-2 min-w-[100px] shadow-sm">
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">{{ $variant->nama_varian }}</span>
                                            <div class="flex items-center justify-between mt-1 gap-1">
                                                <span class="text-xs font-bold {{ $variant->stok <= 5 ? 'text-red-600' : 'text-indigo-600' }}">
                                                    {{ $variant->stok }} <small class="text-gray-400 font-normal">pcs</small>
                                                </span>
                                                <div class="flex items-center gap-1">
                                                    <form action="{{ route('variants.add-stock', $variant->variant_id) }}" method="POST" class="flex items-center">
                                                        @csrf
                                                        <input type="number" name="jumlah_masuk" min="1"
                                                            class="w-10 px-1 py-0.5 text-[10px] border-gray-200 rounded-l focus:ring-0 focus:border-indigo-400"
                                                            placeholder="+0" required>
                                                        <button type="submit" title="Tambah stok"
                                                            class="bg-indigo-600 text-white p-1 rounded-r hover:bg-indigo-700 transition">
                                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('variants.reduce-stock', $variant->variant_id) }}" method="POST" class="flex items-center">
                                                        @csrf
                                                        <input type="number" name="jumlah_kurang" min="1" max="{{ $variant->stok }}"
                                                            class="w-10 px-1 py-0.5 text-[10px] border-gray-200 rounded-l focus:ring-0 focus:border-rose-400"
                                                            placeholder="-0" required>
                                                        <button type="submit" title="Kurangi stok"
                                                            class="{{ $variant->stok == 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-rose-500 hover:bg-rose-600' }} text-white p-1 rounded-r transition"
                                                            {{ $variant->stok == 0 ? 'disabled' : '' }}>
                                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <button type="button"
                                        onclick="openVariantModal('{{ $item->product_id }}', '{{ $item->nama_produk }}')"
                                        class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group">
                                        <i class="fas fa-plus-circle transition-transform group-hover:rotate-90"></i>
                                        TAMBAH VARIAN
                                    </button>
                                </td>

                                {{-- KATEGORI — FIX: whitespace-nowrap + min-width --}}
                                <td class="px-6 py-4 w-40">
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase rounded-md bg-indigo-50 text-indigo-600 border border-indigo-100 whitespace-nowrap">
                                        {{ $item->category->nama_kategori ?? 'Umum' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-gray-400 italic">{{ $item->berat ?? '-' }} gram</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center space-x-3">
                                        <a href="{{ route('products.edit', $item->product_id) }}"
                                            class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-colors border border-amber-100"
                                            title="Edit Produk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('products.stock-history', $item->product_id) }}"
                                            class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors border border-indigo-100"
                                            title="Riwayat Stok">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('products.destroy', $item->product_id) }}" method="POST"
                                            onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors border border-red-100"
                                                title="Hapus Produk">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-box-open text-3xl text-gray-200"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">Belum ada produk</h3>
                                        <p class="text-gray-500 text-sm">Mulai tambah produk dengan variannya sekarang.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH VARIAN --}}
    <div id="variantModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 transform transition-all">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-900">Tambah Varian Baru</h3>
                    <button onclick="closeVariantModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-4">Produk: <span id="modalProductName" class="font-semibold text-indigo-600"></span></p>

                <form id="variantForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 tracking-wider">Nama Varian</label>
                            <input type="text" name="nama_varian"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: Merah, XL, atau 42" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 tracking-wider">Harga Varian</label>
                                <input type="number" name="harga"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Rp" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 tracking-wider">Stok Awal</label>
                                <input type="number" name="stok_awal" value="0"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 tracking-wider">Berat (Gram)</label>
                            <input type="number" name="berat"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: 500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 tracking-wider">Foto Varian (Wajib)</label>
                            <input type="file" name="gambar"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                required>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeVariantModal()"
                            class="flex-1 px-4 py-3 text-sm font-bold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Simpan Varian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openVariantModal(productId, productName) {
            document.getElementById('modalProductName').innerText = productName;
            document.getElementById('variantForm').action = "/products/" + productId + "/add-variant";
            document.getElementById('variantModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeVariantModal() {
            document.getElementById('variantModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.maxHeight = '0';
                    alert.style.marginBottom = '0';
                    alert.style.paddingTop = '0';
                    alert.style.paddingBottom = '0';
                    alert.style.overflow = 'hidden';
                    setTimeout(() => alert.remove(), 1000);
                }, 5000);
            }
        });
    </script>
</x-app-layout>