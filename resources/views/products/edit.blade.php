<x-app-layout>
    <x-slot name="header">
        @if (session('success'))
            <div class="max-w-3xl mx-auto mt-4 px-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Produk: {{ $product->nama_produk }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8">

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-4">
                            <ul>
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.update', $product->product_id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- INFO DASAR --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_produk" value="{{ $product->nama_produk }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3" required>
                            </div>

                            <div class="col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Dasar (Rp) <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="harga-display" inputmode="numeric"
                                        class="pl-10 w-full border border-gray-300 rounded-xl px-4 py-3" placeholder="0"
                                        autocomplete="off">
                                    <input type="hidden" name="harga" id="harga-hidden"
                                        value="{{ $product->harga }}">
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span
                                        class="text-red-500">*</span></label>
                                <select name="category_id" class="w-full border border-gray-300 rounded-xl px-4 py-3"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->category_id }}"
                                            {{ $product->category_id == $cat->category_id ? 'selected' : '' }}>
                                            {{ $cat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                            <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3">{{ $product->deskripsi }}</textarea>
                        </div>

                        {{-- VARIAN --}}
                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h4 class="font-bold text-gray-800">Pengaturan Varian & Stok</h4>
                                    <p class="text-xs text-gray-400 mt-1">Tentukan berat spesifik untuk tiap varian.</p>
                                </div>
                                <button type="button" onclick="addVariantRow()"
                                    class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl font-bold hover:bg-indigo-100">
                                    + Tambah Varian
                                </button>
                            </div>

                            <div id="variant-list" class="space-y-3">
                                @foreach ($product->variants as $index => $variant)
                                    <div
                                        class="variant-row grid grid-cols-12 gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-100 items-center">
                                        <input type="hidden" name="variants[{{ $index }}][variant_id]"
                                            value="{{ $variant->variant_id }}">
                                        <div class="col-span-4">
                                            <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">Nama
                                                Varian</label>
                                            <input type="text" name="variants[{{ $index }}][nama_varian]"
                                                class="variant-name-input w-full border-gray-300 rounded-xl text-sm"
                                                value="{{ $variant->nama_varian }}" oninput="refreshImageDropdowns()"
                                                required>
                                        </div>
                                        <div class="col-span-2">
                                            <label
                                                class="text-[10px] uppercase text-gray-400 font-bold block mb-1">Stok</label>
                                            <input type="number" name="variants[{{ $index }}][stok]"
                                                value="{{ $variant->stok }}"
                                                class="w-full border-gray-300 rounded-xl text-sm" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label
                                                class="text-[10px] uppercase text-gray-400 font-bold block mb-1">Berat
                                                (Gr)
                                            </label>
                                            <input type="number" name="variants[{{ $index }}][berat]"
                                                value="{{ $variant->berat }}"
                                                class="w-full border-gray-300 rounded-xl text-sm" required>
                                        </div>
                                        <div class="col-span-3">
                                            <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">+
                                                Harga (Rp)</label>
                                            <input type="number" name="variants[{{ $index }}][harga_tambahan]"
                                                value="{{ $variant->harga_tambahan }}"
                                                class="w-full border-gray-300 rounded-xl text-sm">
                                        </div>
                                        <div class="col-span-1 text-center pt-5">
                                            <button type="button" onclick="removeVariantRow(this)"
                                                class="text-red-400 hover:text-red-600 text-xl font-bold leading-none">&times;</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- GAMBAR EXISTING --}}
                        <div class="border-t border-gray-100 pt-6">
                            <h4 class="font-bold text-gray-800 mb-4">Foto Saat Ini</h4>
                            @if ($product->images->count() > 0)
                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    @foreach ($product->images as $img)
                                        <div class="relative group aspect-square">
                                            <img src="{{ asset('storage/' . $img->gambar) }}"
                                                class="w-full h-full object-cover rounded-2xl border border-gray-200">
                                            @if ($img->is_primary)
                                                <span
                                                    class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Utama</span>
                                            @endif
                                            @if ($img->variant)
                                                <span
                                                    class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $img->variant->nama_varian }}</span>
                                            @endif
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center">
                                                <button type="button"
                                                    onclick="if(confirm('Hapus gambar ini?')) { window.location.href='/product-image/delete/{{ $img->product_image_id }}' }"
                                                    class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 mb-4">Belum ada foto.</p>
                            @endif

                            {{-- UPLOAD FOTO BARU — sama kayak create --}}
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-sm font-semibold text-gray-700">Tambah Foto Baru & Tag
                                    Varian</label>
                                <span class="text-xs text-gray-400">Centang varian yang sesuai per foto</span>
                            </div>
                            <div id="image-upload-container" class="space-y-3"></div>
                            <button type="button" onclick="addImageRow()"
                                class="mt-3 text-xs font-bold text-blue-600 hover:text-blue-800">+ Tambah Foto
                                Lain</button>
                        </div>

                        <div class="flex justify-between items-center pt-8 border-t">
                            <a href="{{ route('products.index') }}" class="text-gray-600 font-medium">Batal</a>
                            <button type="submit"
                                class="px-8 py-3 bg-blue-600 text-white rounded-xl shadow-lg font-bold hover:bg-blue-700 transition">
                                Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // variantIdx mulai dari jumlah varian yang sudah ada
        let variantIdx = {{ $product->variants->count() }};

        // ============================================================
        // VARIAN
        // ============================================================
        function addVariantRow(defaultName = "", defaultStock = "", defaultPrice = "", defaultWeight = "1000") {
            const list = document.getElementById('variant-list');
            const html = `
            <div class="variant-row grid grid-cols-12 gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-100 items-center">
                <div class="col-span-4">
                    <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">Nama Varian</label>
                    <input type="text"
                        name="variants[${variantIdx}][nama_varian]"
                        class="variant-name-input w-full border-gray-300 rounded-xl text-sm"
                        value="${defaultName}"
                        placeholder="cth: Hitam, XL, Gold..."
                        oninput="refreshImageDropdowns()"
                        required>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">Stok</label>
                    <input type="number" name="variants[${variantIdx}][stok]"
                        class="w-full border-gray-300 rounded-xl text-sm" value="${defaultStock}" placeholder="0" required>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">Berat (Gr)</label>
                    <input type="number" name="variants[${variantIdx}][berat]"
                        class="w-full border-gray-300 rounded-xl text-sm" value="${defaultWeight}" placeholder="Gram" required>
                </div>
                <div class="col-span-3">
                    <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">+ Harga (Rp)</label>
                    <input type="number" name="variants[${variantIdx}][harga_tambahan]"
                        class="w-full border-gray-300 rounded-xl text-sm" value="${defaultPrice}" placeholder="0">
                </div>
                <div class="col-span-1 text-center pt-5">
                    <button type="button" onclick="removeVariantRow(this)"
                        class="text-red-400 hover:text-red-600 text-xl font-bold leading-none">&times;</button>
                </div>
            </div>`;
            list.insertAdjacentHTML('beforeend', html);
            variantIdx++;
            refreshImageDropdowns();
        }

        function removeVariantRow(btn) {
            btn.closest('.variant-row').remove();
            refreshImageDropdowns();
        }

        // ============================================================
        // FOTO — checkbox multi-varian (sama persis kayak create)
        // ============================================================
        let imageRowIdx = 0;

        function getVarianNames() {
            return [...document.querySelectorAll('.variant-name-input')]
                .map(i => i.value.trim())
                .filter(v => v !== '');
        }

        function refreshImageDropdowns() {
            const names = getVarianNames();
            document.querySelectorAll('.varian-checkbox-group').forEach(group => {
                const rowIdx = group.dataset.rowIdx;
                const checked = new Set(
                    [...group.querySelectorAll('input[type=checkbox]:checked')].map(cb => cb.value)
                );
                group.innerHTML = names.length === 0 ?
                    '<span class="text-xs text-gray-400 italic">Isi nama varian dulu di atas</span>' :
                    names.map(n => `
                        <label class="flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                            <input type="checkbox"
                                name="image_tags[${rowIdx}][]"
                                value="${n}"
                                class="rounded"
                                ${checked.has(n) ? 'checked' : ''}>
                            <span class="text-sm text-gray-700">${n}</span>
                        </label>`).join('');
            });
        }

        function buildImageRow(isFirst = false) {
            const idx = imageRowIdx++;
            const names = getVarianNames();
            const checkboxesHtml = names.length === 0 ?
                '<span class="text-xs text-gray-400 italic">Isi nama varian dulu di atas</span>' :
                names.map(n => `
                    <label class="flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                        <input type="checkbox" name="image_tags[${idx}][]" value="${n}" class="rounded">
                        <span class="text-sm text-gray-700">${n}</span>
                    </label>`).join('');

            const removeBtn = isFirst ?
                '' :
                `<button type="button" onclick="this.closest('.image-row').remove()"
                    class="text-red-400 hover:text-red-600 text-xl font-bold leading-none self-start pt-1 flex-shrink-0">&times;</button>`;

            return `
            <div class="image-row ${isFirst ? 'bg-blue-50/50 border-blue-100' : 'bg-gray-50 border-gray-100'} p-4 rounded-xl border space-y-3">
                <div class="flex gap-3 items-center">
                    <input type="file" name="images[]" accept="image/jpg,image/jpeg,image/png" class="text-sm flex-shrink-0">
                    <label class="flex items-center gap-1.5 text-xs text-gray-500 flex-shrink-0 whitespace-nowrap cursor-pointer ml-auto">
                        <input type="checkbox" name="is_primary_idx[]" value="${idx}"
                            class="primary-checkbox"
                            onchange="handlePrimaryCheck(this)">
                        Foto utama
                    </label>
                    ${removeBtn}
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-2">Tag ke varian (boleh lebih dari satu)</p>
                    <div class="varian-checkbox-group flex flex-wrap gap-x-4 gap-y-2" data-row-idx="${idx}">
                        ${checkboxesHtml}
                    </div>
                </div>
            </div>`;
        }

        function addImageRow() {
            const container = document.getElementById('image-upload-container');
            container.insertAdjacentHTML('beforeend', buildImageRow(false));
        }

        function handlePrimaryCheck(cb) {
            if (cb.checked) {
                document.querySelectorAll('.primary-checkbox').forEach(other => {
                    if (other !== cb) other.checked = false;
                });
            }
        }

        // ============================================================
        // Init — tambah 1 foto row kosong, dan pasang oninput ke varian existing
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Pasang refreshImageDropdowns ke varian yang sudah ada (dari blade loop)
            document.querySelectorAll('.variant-name-input').forEach(input => {
                input.addEventListener('input', refreshImageDropdowns);
            });

            // Tambah 1 foto row kosong untuk upload baru
            const container = document.getElementById('image-upload-container');
            container.insertAdjacentHTML('beforeend', buildImageRow(true));
        });

        // ============================================================
        // FORMAT HARGA — titik ribuan otomatis
        // ============================================================
        function formatRibuan(val) {
            return val.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function initHargaInput() {
            const displayInput = document.getElementById('harga-display');
            const hiddenInput = document.getElementById('harga-hidden');
            if (!displayInput || !hiddenInput) return;

            // Set nilai awal dari harga existing (sudah ada di hidden input)
            const rawAwal = String(hiddenInput.value).split('.')[0].replace(/\D/g, '');
            displayInput.value = rawAwal ? formatRibuan(rawAwal) : '';

            displayInput.addEventListener('input', function() {
                const raw = this.value.replace(/\./g, '');
                hiddenInput.value = raw;
                this.value = formatRibuan(this.value);
            });

            displayInput.addEventListener('keydown', function(e) {
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
                if (!/^\d$/.test(e.key) && !allowed.includes(e.key)) {
                    e.preventDefault();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initHargaInput);
    </script>
</x-app-layout>
