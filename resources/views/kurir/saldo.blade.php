<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        {{-- HEADER --}}
        <div class="bg-white border-b border-slate-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('kurir.index') }}" class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500">Kurir</p>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Saldo & Penghasilan</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- KARTU SALDO --}}
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl shadow-blue-100">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-200">Saldo Tersedia</p>
                        <p class="text-4xl font-black mt-2 tracking-tight">
                            Rp {{ number_format($kurir->saldo, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-blue-200 mt-2 font-medium">{{ $kurir->name }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-5 pt-5 border-t border-white/20 flex items-center gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-200">Total Penghasilan</p>
                        <p class="text-lg font-black mt-0.5">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-200">Pengiriman Selesai</p>
                        <p class="text-lg font-black mt-0.5">{{ $riwayat->total() }} paket</p>
                    </div>
                </div>
            </div>

            {{-- RIWAYAT PENGHASILAN --}}
            <div class="bg-white rounded-3xl border border-slate-100">
                <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                    <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Riwayat Penghasilan</h2>
                </div>

                @if($riwayat->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="font-black text-slate-400 text-sm">Belum ada penghasilan</p>
                        <p class="text-xs text-slate-300 mt-1">Selesaikan pengiriman untuk mendapat ongkir</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($riwayat as $order)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition-colors">

                            {{-- Icon --}}
                            <div class="w-10 h-10 bg-emerald-50 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-slate-800 text-sm">Order #{{ $order->order_id }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $order->user->name }}
                                    @if($order->shippingOption)
                                        · <span class="text-indigo-400">{{ $order->shippingOption->label }}</span>
                                    @endif
                                </p>
                                <p class="text-[10px] text-slate-300 mt-0.5">
                                    {{ \Carbon\Carbon::parse($order->updated_at)->translatedFormat('d M Y, H:i') }} WIB
                                </p>
                            </div>

                            {{-- Nominal --}}
                            <div class="text-right shrink-0">
                                <p class="font-black text-emerald-600 text-sm">
                                    +Rp {{ number_format($order->ongkir, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-slate-300 mt-0.5">Ongkir</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- PAGINATION --}}
                    @if($riwayat->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $riwayat->links() }}
                    </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>