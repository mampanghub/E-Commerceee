<x-app-layout>
    {{-- HERO SECTION --}}
    <div class="relative overflow-hidden bg-white border-b border-slate-100">
        {{-- Background Accent --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[100%] bg-blue-400 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-2 tracking-tighter">
                        Verifikasi <span class="text-blue-600">Toko</span>
                    </h2>
                    <p class="text-base text-slate-500 font-medium leading-relaxed">
                        Cek dan setujui pendaftaran toko baru untuk menjaga kualitas komunitas, Cu.
                    </p>
                </div>

                <div class="hidden md:block">

                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6">
                @forelse($stores as $store)
                    <div class="group bg-white rounded-[2.5rem] p-8 border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1">
                        <div class="flex gap-6 items-center">
                            {{-- Icon Toko --}}
                            <div class="hidden sm:flex w-16 h-16 bg-slate-50 text-slate-400 rounded-[1.5rem] items-center justify-center border border-slate-100 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>

                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ $store->nama_toko }}</h3>
                                    <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-amber-200">
                                        Pending
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-y-2 text-sm">
                                    <div class="flex items-center text-slate-600 font-medium">
                                        <span class="text-slate-400 mr-1 font-normal uppercase text-[10px] tracking-wider">Pemilik:</span>
                                        {{ $store->user->name }}
                                    </div>
                                    <span class="mx-3 text-slate-200 hidden sm:block">|</span>
                                    <div class="flex items-center text-slate-600 font-medium">
                                        <span class="text-slate-400 mr-1 font-normal uppercase text-[10px] tracking-wider">Kontak:</span>
                                        {{ $store->user->no_telp ?? '-' }}
                                    </div>
                                </div>

                                <p class="text-[11px] text-slate-400 mt-3 font-bold uppercase tracking-widest flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Terdaftar: {{ $store->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-slate-50">
                            {{-- Tombol Tolak --}}
                            <form action="{{ route('admin.stores.reject', $store->store_id) }}" method="POST" class="flex-1 md:flex-none">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Yakin mau nolak toko ini?')"
                                        class="w-full px-6 py-4 bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:bg-red-50 hover:border-red-100 rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                    Tolak
                                </button>
                            </form>

                            {{-- Tombol Setujui --}}
                            <form action="{{ route('admin.stores.approve', $store->store_id) }}" method="POST" class="flex-1 md:flex-none">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full px-8 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.15em] transition-all hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-100 active:scale-95 shadow-lg shadow-blue-50">
                                    Setujui Toko
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="bg-white p-20 rounded-[3rem] text-center border-2 border-dashed border-slate-200">
                        <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tighter italic">Semua Beres, Cu!</h3>
                        <p class="text-slate-500 font-medium mt-2">Gak ada toko yang nunggu antrean verifikasi saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
