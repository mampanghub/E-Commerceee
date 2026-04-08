{{-- resources/views/profile/_address_modal.blade.php --}}
{{-- Include di mana saja dengan: @include('profile._address_modal') --}}

<div id="address-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeAddressModal()"></div>
    <div class="relative min-h-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-6 my-8">

            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-black text-slate-900" id="addr-modal-title">Tambah Alamat</h2>
                <button onclick="closeAddressModal()" class="p-2 bg-slate-100 rounded-xl hover:bg-slate-200">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="addr-form" method="POST" class="space-y-4">
                @csrf
                <div id="addr-method-field"></div>

                <div>
                    <label
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Label</label>
                    <input type="text" name="label" id="af-label" placeholder="Rumah / Kantor / dll"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Nama
                            Penerima</label>
                        <input type="text" name="nama_penerima" id="af-nama"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">No.
                            Telepon</label>
                        <input type="text" name="no_telp" id="af-telp"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            inputmode="numeric" pattern="[0-9+\-\s]*"
                            oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" required>
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Alamat</label>
                    <textarea name="alamat" id="af-alamat" rows="2"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                        required></textarea>
                </div>

                <div>
                    <label
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Provinsi</label>
                    <select name="province_id" id="af-province"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach (\Laravolt\Indonesia\Models\Province::orderBy('name')->get() as $prov)
                            <option value="{{ $prov->code }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Kota /
                        Kabupaten</label>
                    <select name="city_id" id="af-city"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Kecamatan</label>
                    <select name="district_id" id="af-district"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Kelurahan</label>
                    <select name="village_id" id="af-village"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_default" id="af-default" value="1"
                        class="w-4 h-4 accent-blue-600">
                    <span class="text-sm font-bold text-slate-700">Jadikan alamat utama</span>
                </label>

                <button type="submit"
                    class="w-full py-3.5 bg-blue-600 text-white font-black text-sm rounded-2xl hover:bg-blue-700 transition-all">
                    Simpan Alamat
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddressModal(opts = {}) {
        const {
            title = 'Tambah Alamat', action, editId, data
        } = opts;
        document.getElementById('addr-modal-title').textContent = title;
        document.getElementById('addr-form').action = action || '{{ route('addresses.store') }}';
        document.getElementById('addr-method-field').innerHTML = editId ?
            '<input type="hidden" name="_method" value="PATCH">' : '';

        // Reset form
        document.getElementById('addr-form').reset();
        ['af-city', 'af-district', 'af-village'].forEach(id => {
            const el = document.getElementById(id);
            const label = {
                'af-city': 'Kota',
                'af-district': 'Kecamatan',
                'af-village': 'Kelurahan'
            } [id];
            el.innerHTML = `<option value="">-- Pilih ${label} --</option>`;
        });

        if (data) {
            document.getElementById('af-label').value = data.label || '';
            document.getElementById('af-nama').value = data.nama_penerima || '';
            document.getElementById('af-telp').value = data.no_telp || '';
            document.getElementById('af-alamat').value = data.alamat || '';
            document.getElementById('af-default').checked = data.is_default || false;

            if (data.province_id) {
                document.getElementById('af-province').value = data.province_id;
                afLoadCity(data.province_id, data.city_id).then(() =>
                    afLoadDistrict(data.city_id, data.district_id).then(() =>
                        afLoadVillage(data.district_id, data.village_id)
                    )
                );
            }
        }

        document.getElementById('address-modal').classList.remove('hidden');
    }

    // Shortcut buat halaman profil
    function openModal() {
        openAddressModal();
    }

    function openEditModal(id, data) {
        openAddressModal({
            title: 'Edit Alamat',
            action: `/profile/addresses/${id}`,
            editId: id,
            data
        });
    }

    function closeAddressModal() {
        document.getElementById('address-modal').classList.add('hidden');
    }

    async function afFetch(url) {
        const res = await fetch(url);
        return res.json();
    }

    async function afLoadCity(provId, selected = null) {
        const data = await afFetch(`/api/get-cities?province_id=${provId}`);
        const sel = document.getElementById('af-city');
        sel.innerHTML = '<option value="">-- Pilih Kota --</option>';
        data.forEach(item => {
            const o = document.createElement('option');
            o.value = item.code;
            o.textContent = item.name;
            if (selected && item.code == selected) o.selected = true;
            sel.appendChild(o);
        });
    }

    async function afLoadDistrict(cityId, selected = null) {
        const data = await afFetch(`/api/get-districts?city_id=${cityId}`);
        const sel = document.getElementById('af-district');
        sel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        data.forEach(item => {
            const o = document.createElement('option');
            o.value = item.code;
            o.textContent = item.name;
            if (selected && item.code == selected) o.selected = true;
            sel.appendChild(o);
        });
    }

    async function afLoadVillage(districtId, selected = null) {
        const data = await afFetch(`/api/get-villages?district_id=${districtId}`);
        const sel = document.getElementById('af-village');
        sel.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        data.forEach(item => {
            const o = document.createElement('option');
            o.value = item.code;
            o.textContent = item.name;
            if (selected && item.code == selected) o.selected = true;
            sel.appendChild(o);
        });
    }

    document.getElementById('af-province').addEventListener('change', function() {
        afLoadCity(this.value);
        document.getElementById('af-district').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        document.getElementById('af-village').innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
    });
    document.getElementById('af-city').addEventListener('change', function() {
        afLoadDistrict(this.value);
        document.getElementById('af-village').innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
    });
    document.getElementById('af-district').addEventListener('change', function() {
        afLoadVillage(this.value);
    });
</script>
