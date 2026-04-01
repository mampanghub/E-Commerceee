<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('profile.edit') }}" class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500">Profil</p>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Alamat Saya</h1>
                    </div>
                    <button onclick="openModal()"
                        class="ml-auto flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-black rounded-2xl hover:bg-blue-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Alamat
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl font-semibold text-sm">✓ {{ session('success') }}</div>
            @endif

            @forelse($addresses as $addr)
            <div class="bg-white rounded-3xl border {{ $addr->is_default ? 'border-blue-200' : 'border-slate-100' }} p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-black rounded-lg">{{ $addr->label }}</span>
                            @if($addr->is_default)
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-black rounded-lg border border-blue-100">Utama</span>
                            @endif
                        </div>
                        <p class="font-black text-slate-900">{{ $addr->nama_penerima }}</p>
                        <p class="text-sm text-blue-600 font-bold mt-0.5">{{ $addr->no_telp }}</p>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $addr->alamat_lengkap }}</p>
                    </div>
                    <div class="flex flex-col gap-2 shrink-0">
                        <button onclick="openEditModal({{ $addr->address_id }}, @json($addr))"
                            class="px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-black rounded-xl hover:bg-slate-200 transition-all text-center">
                            Edit
                        </button>
                        @if(!$addr->is_default)
                        <form action="{{ route('addresses.set-default', $addr->address_id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-black rounded-xl hover:bg-blue-100 transition-all">Utamakan</button>
                        </form>
                        <form action="{{ route('addresses.destroy', $addr->address_id) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full px-3 py-1.5 bg-red-50 text-red-500 text-xs font-black rounded-xl hover:bg-red-100 transition-all">Hapus</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center">
                <p class="font-black text-slate-700">Belum ada alamat tersimpan</p>
                <p class="text-slate-400 text-sm mt-1">Tambahkan alamat pengiriman kamu.</p>
            </div>
            @endforelse
        </div>
    </div>

    @include('profile._address_modal')
</x-app-layout>