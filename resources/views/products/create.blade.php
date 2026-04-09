<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}"
                class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Admin Panel</p>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Produk Baru</h1>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8">

                    @if (session('error'))
                        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-4">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-4">
                            <ul>
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        {{-- INFO DASAR --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_produk"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3"
                                    placeholder="Masukkan nama produk" required>
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
                                    <input type="hidden" name="harga" id="harga-hidden">
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span
                                        class="text-red-500">*</span></label>
                                <select id="category-select" name="category_id" onchange="toggleVariants()"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->category_id }}"
                                            data-nama="{{ strtolower($cat->nama_kategori) }}">
                                            {{ $cat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                            <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3"
                                placeholder="Masukkan deskripsi produk"></textarea>
                        </div>

                        {{-- VARIAN --}}
                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h4 class="font-bold text-gray-800">Pengaturan Varian & Stok</h4>
                                    <p class="text-xs text-gray-400 mt-1">Tentukan berat spesifik untuk tiap varian.</p>
                                </div>
                                <button type="button" onclick="addVariantRow()"
                                    class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl font-bold hover:bg-indigo-100">+
                                    Tambah Varian</button>
                            </div>
                            <div id="variant-list" class="space-y-3"></div>
                        </div>

                        {{-- FOTO --}}
                        <div class="border-t border-gray-100 pt-6">
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-sm font-semibold text-gray-700">Upload Foto & Tag
                                    Varian</label>
                                <span class="text-xs text-gray-400">Pilih varian dari dropdown — otomatis ngikutin yang
                                    lo isi di atas</span>
                            </div>
                            <div id="image-upload-container" class="space-y-3"></div>
                            <button type="button" onclick="addImageRow()"
                                class="mt-3 text-xs font-bold text-blue-600 hover:text-blue-800">+ Tambah Foto
                                Lain</button>
                        </div>

                        <div class="flex justify-end pt-8">
                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-md font-bold">Simpan
                                Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let variantIdx = 0;

        // ============================================================
        // VARIAN — setiap kali nama varian berubah, refresh semua dropdown foto
        // ============================================================
        function addVariantRow(defaultName = "", defaultStock = "", defaultPrice = "", defaultWeight = "1000") {
            const list = document.getElementById('variant-list');
            const isFirst = variantIdx === 0;
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
                    <input type="number" name="variants[${variantIdx}][stok]" class="w-full border-gray-300 rounded-xl text-sm" value="${defaultStock}" placeholder="0" required>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">Berat (Gr)</label>
                    <input type="number" name="variants[${variantIdx}][berat]" class="w-full border-gray-300 rounded-xl text-sm" value="${defaultWeight}" placeholder="Gram" required>
                </div>
                <div class="col-span-3">
                    <label class="text-[10px] uppercase text-gray-400 font-bold block mb-1">+ Harga (Rp)</label>
                    <input type="number" name="variants[${variantIdx}][harga_tambahan]" class="w-full border-gray-300 rounded-xl text-sm" value="${defaultPrice}" placeholder="0">
                </div>
                <div class="col-span-1 text-center pt-5">
                    ${isFirst
                        ? '<span class="text-gray-300 text-sm">--</span>'
                        : `<button type="button" onclick="removeVariantRow(this)" class="text-red-400 hover:text-red-600 text-xl font-bold leading-none">&times;</button>`
                    }
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
        // FOTO — checkbox multi-varian per foto
        // ============================================================
        let imageRowIdx = 0;

        function getVarianNames() {
            return [...document.querySelectorAll('.variant-name-input')]
                .map(i => i.value.trim())
                .filter(v => v !== '');
        }

        // Rebuild checkbox list di tiap foto row, pertahankan yang sudah dicentang
        function refreshImageDropdowns() {
            const names = getVarianNames();
            document.querySelectorAll('.varian-checkbox-group').forEach(group => {
                const rowIdx = group.dataset.rowIdx;
                // Simpan yang sudah dicentang
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
                `<button type="button" onclick="this.closest('.image-row').remove()" class="text-red-400 hover:text-red-600 text-xl font-bold leading-none self-start pt-1 flex-shrink-0">&times;</button>`;

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

        // Pastiin cuma satu yang bisa jadi foto utama
        function handlePrimaryCheck(cb) {
            if (cb.checked) {
                document.querySelectorAll('.primary-checkbox').forEach(other => {
                    if (other !== cb) other.checked = false;
                });
            }
        }

        // ============================================================
        // Toggle varian berdasarkan kategori (logika lo yang lama, dipertahanin)
        // ============================================================
        function toggleVariants() {
            const select = document.getElementById('category-select');
            if (!select) return;
            const categoryName = (select.options[select.selectedIndex].getAttribute('data-nama') || '').toLowerCase();
            const list = document.getElementById('variant-list');
            list.innerHTML = '';
            variantIdx = 0;

            if (categoryName.includes('pakaian') || categoryName.includes('baju')) {
                addVariantRow("Hitam", 10, 0, 250);
                addVariantRow("Putih", 10, 0, 250);
            } else if (categoryName.includes('perkakas') || categoryName.includes('rumah')) {
                addVariantRow("Baja Steel", 5, 0, 1500);
            } else {
                addVariantRow("", "", "", 1000);
            }
        }

        // ============================================================
        // Init — jalankan pas halaman pertama load
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            addVariantRow("", "", "", 1000); // 1 varian kosong
            const container = document.getElementById('image-upload-container');
            container.insertAdjacentHTML('beforeend', buildImageRow(true)); // 1 foto row pertama
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

            displayInput.addEventListener('input', function() {
                const raw = this.value.replace(/\./g, '');
                hiddenInput.value = raw;
                this.value = formatRibuan(this.value);
            });

            displayInput.addEventListener('keydown', function(e) {
                // Boleh: angka, backspace, delete, arrow, tab
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
                if (!/^\d$/.test(e.key) && !allowed.includes(e.key)) {
                    e.preventDefault();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initHargaInput);
    </script>
</x-app-layout>
