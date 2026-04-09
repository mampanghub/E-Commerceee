@use('Illuminate\Support\Facades\Cache')

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Admin Panel</p>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Monitoring User</h1>
            </div>
            <a href="{{ route('admin.users.create-kurir') }}" id="btn-create-kurir"
                class="hidden items-center gap-2 px-6 py-3 bg-orange-500 text-white rounded-2xl font-black text-sm hover:bg-orange-600 transition-all shadow-lg shadow-orange-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kurir
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-3xl border border-slate-100 p-6 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900">{{ $totalPembeli }}</p>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Total Pembeli</p>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-slate-100 p-6 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900">{{ $totalKurir }}</p>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Total Kurir</p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl font-bold text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- TAB --}}
            <div class="flex gap-2 bg-white border border-slate-100 p-1.5 rounded-2xl shadow-sm w-fit">
                <button onclick="switchTab('pembeli')" id="tab-pembeli"
                    class="tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-blue-600 text-white shadow-md shadow-blue-100">
                    Pembeli
                </button>
                <button onclick="switchTab('kurir')" id="tab-kurir"
                    class="tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-700">
                    Kurir
                </button>
            </div>

            {{-- TABLE --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest" id="table-title">Daftar Pembeli</h3>
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Live</span>
                    </div>
                </div>

                {{-- PEMBELI --}}
                <div id="content-pembeli" class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-0">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">User / Email</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">No. Telp</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($pembeliUsers as $user)
                                @php
                                    $userId = $user->user_id ?? $user->id;
                                    $onlineStatus = Cache::get('user-is-online-' . $userId) === true;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative shrink-0">
                                                <img src="{{ Avatar::create($user->name)->toBase64() }}"
                                                    class="w-10 h-10 rounded-2xl object-cover">
                                                @if($onlineStatus)
                                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-800">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-bold text-slate-600">{{ $user->no_telp ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($onlineStatus)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-xl">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                Online
                                            </span>
                                        @elseif($user->last_login_at)
                                            <div class="flex flex-col items-end">
                                                <span class="text-xs font-bold text-slate-700">{{ $user->last_login_at->diffForHumans() }}</span>
                                                <span class="text-[10px] text-slate-400">{{ $user->last_login_at->format('d M Y • H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Belum pernah login</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-slate-100">{{ $pembeliUsers->links() }}</div>
                </div>

                {{-- KURIR --}}
                <div id="content-kurir" class="hidden overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kurir / Email</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">No. Telp</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($kurirUsers as $user)
                                @php
                                    $userId = $user->user_id ?? $user->id;
                                    $onlineStatus = Cache::get('user-is-online-' . $userId) === true;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative shrink-0">
                                                <img src="{{ Avatar::create($user->name)->toBase64() }}"
                                                    class="w-10 h-10 rounded-2xl object-cover">
                                                @if($onlineStatus)
                                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-800">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-bold text-slate-600">{{ $user->no_telp ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($onlineStatus)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-xl">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                Online
                                            </span>
                                        @elseif($user->last_login_at)
                                            <div class="flex flex-col items-end">
                                                <span class="text-xs font-bold text-slate-700">{{ $user->last_login_at->diffForHumans() }}</span>
                                                <span class="text-[10px] text-slate-400">{{ $user->last_login_at->format('d M Y • H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Belum pernah login</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST"
                                            onsubmit="return confirm('Hapus kurir {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-1.5 bg-red-50 text-red-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-slate-100">{{ $kurirUsers->links() }}</div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.getElementById('content-pembeli').classList.toggle('hidden', tab !== 'pembeli');
            document.getElementById('content-kurir').classList.toggle('hidden', tab !== 'kurir');

            const btnKurir = document.getElementById('btn-create-kurir');
            tab === 'kurir' ? btnKurir.classList.replace('hidden', 'inline-flex') : btnKurir.classList.replace('inline-flex', 'hidden');

            document.getElementById('tab-pembeli').className = tab === 'pembeli'
                ? 'tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-blue-600 text-white shadow-md shadow-blue-100'
                : 'tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-700';

            document.getElementById('tab-kurir').className = tab === 'kurir'
                ? 'tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-orange-500 text-white shadow-md shadow-orange-100'
                : 'tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-700';

            document.getElementById('table-title').textContent = tab === 'pembeli' ? 'Daftar Pembeli' : 'Daftar Kurir';
        }
    </script>
</x-app-layout>
