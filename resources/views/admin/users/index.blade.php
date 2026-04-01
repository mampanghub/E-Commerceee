@use('Illuminate\Support\Facades\Cache')
@use('Carbon\Carbon')

<x-app-layout>
    {{-- HERO SECTION MONITORING --}}
    <div class="relative overflow-hidden bg-white border-b border-slate-100">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[80%] bg-blue-400 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[80%] bg-indigo-400 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-left">
                    <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-xs font-black uppercase tracking-widest rounded-full mb-4">
                        Admin Dashboard
                    </span>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-2 tracking-tighter">
                        Monitoring <span class="text-blue-600">User</span>
                    </h1>
                    <p class="text-base text-slate-500 font-medium leading-relaxed">
                        Pantau aktivitas login dan status pengguna secara real-time.
                    </p>
                </div>

                <div class="flex gap-4">
                    {{-- Total Pembeli --}}
                    <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-slate-900 leading-none">{{ $totalPembeli }}</div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pembeli</div>
                        </div>
                    </div>

                    {{-- Total Kurir --}}
                    <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-slate-900 leading-none">{{ $totalKurir }}</div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kurir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- FLASH MESSAGE --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl font-semibold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TAB SWITCHER --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex gap-2 bg-white border border-slate-100 p-1.5 rounded-2xl shadow-sm">
                    <button onclick="switchTab('pembeli')" id="tab-pembeli"
                        class="tab-btn px-6 py-2.5 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-200 bg-blue-600 text-white shadow-md shadow-blue-100">
                        Pembeli
                    </button>
                    <button onclick="switchTab('kurir')" id="tab-kurir"
                        class="tab-btn px-6 py-2.5 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-200 text-slate-400 hover:text-slate-700">
                        Kurir
                    </button>
                </div>

                {{-- Tombol Create Kurir (hanya muncul di tab kurir) --}}
                <a href="{{ route('admin.users.create-kurir') }}" id="btn-create-kurir"
                    class="hidden items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-2xl font-black text-sm shadow-lg shadow-orange-100 hover:shadow-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Kurir
                </a>
            </div>

            {{-- TABLE CONTAINER --}}
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-8 px-2">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight" id="table-title">Daftar Pembeli</h3>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Live Update</span>
                    </div>
                </div>

                {{-- TABLE PEMBELI --}}
                <div id="content-pembeli">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                                    <th class="px-6 pb-2">User / Email</th>
                                    <th class="px-6 pb-2 text-center">No. Telp</th>
                                    <th class="px-6 pb-2 text-right">Status / Terakhir Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembeliUsers as $user)
                                    @php
                                        $userId = $user->user_id ?? $user->id;
                                        $onlineStatus = Cache::get('user-is-online-' . $userId) === true;
                                    @endphp
                                    <tr class="group transition-all duration-300">
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 rounded-l-[1.5rem] transition-all border-y border-l border-transparent group-hover:border-slate-100">
                                            <div class="flex items-center gap-4">
                                                <div class="relative">
                                                    <img src="{{ Avatar::create($user->name)->toBase64() }}"
                                                         class="w-12 h-12 rounded-2xl object-cover ring-4 ring-white shadow-sm">
                                                    @if($onlineStatus)
                                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-slate-800 group-hover:text-blue-600 transition-colors uppercase tracking-tight">{{ $user->name }}</div>
                                                    <div class="text-xs text-slate-400 font-medium">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 transition-all border-y border-transparent group-hover:border-slate-100 text-center">
                                            <span class="text-sm font-semibold text-slate-600">{{ $user->no_telp ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 rounded-r-[1.5rem] transition-all border-y border-r border-transparent group-hover:border-slate-100 text-right">
                                            @if ($onlineStatus)
                                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                    Online
                                                </div>
                                            @elseif ($user->last_login_at)
                                                <div class="flex flex-col items-end">
                                                    <span class="text-xs font-bold text-slate-700">{{ $user->last_login_at->diffForHumans() }}</span>
                                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">{{ $user->last_login_at->format('d M Y • H:i') }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-300 italic text-[10px] font-bold uppercase tracking-widest">Belum pernah login</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-10">{{ $pembeliUsers->links() }}</div>
                </div>

                {{-- TABLE KURIR --}}
                <div id="content-kurir" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                                    <th class="px-6 pb-2">Kurir / Email</th>
                                    <th class="px-6 pb-2 text-center">No. Telp</th>
                                    <th class="px-6 pb-2 text-center">Status / Terakhir Aktif</th>
                                    <th class="px-6 pb-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kurirUsers as $user)
                                    @php
                                        $userId = $user->user_id ?? $user->id;
                                        $onlineStatus = Cache::get('user-is-online-' . $userId) === true;
                                    @endphp
                                    <tr class="group transition-all duration-300">
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 rounded-l-[1.5rem] transition-all border-y border-l border-transparent group-hover:border-slate-100">
                                            <div class="flex items-center gap-4">
                                                <div class="relative">
                                                    <img src="{{ Avatar::create($user->name)->toBase64() }}"
                                                         class="w-12 h-12 rounded-2xl object-cover ring-4 ring-white shadow-sm">
                                                    @if($onlineStatus)
                                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-slate-800 group-hover:text-orange-500 transition-colors uppercase tracking-tight">{{ $user->name }}</div>
                                                    <div class="text-xs text-slate-400 font-medium">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 transition-all border-y border-transparent group-hover:border-slate-100 text-center">
                                            <span class="text-sm font-semibold text-slate-600">{{ $user->no_telp ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 transition-all border-y border-transparent group-hover:border-slate-100 text-center">
                                            @if ($onlineStatus)
                                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                    Online
                                                </div>
                                            @elseif ($user->last_login_at)
                                                <div class="flex flex-col items-end">
                                                    <span class="text-xs font-bold text-slate-700">{{ $user->last_login_at->diffForHumans() }}</span>
                                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">{{ $user->last_login_at->format('d M Y • H:i') }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-300 italic text-[10px] font-bold uppercase tracking-widest">Belum pernah login</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 bg-slate-50 group-hover:bg-white group-hover:shadow-2xl group-hover:shadow-slate-100 rounded-r-[1.5rem] transition-all border-y border-r border-transparent group-hover:border-slate-100 text-center">
                                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST"
                                                onsubmit="return confirm('Hapus kurir {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
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
                    </div>
                    <div class="mt-10">{{ $kurirUsers->links() }}</div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Toggle content
            document.getElementById('content-pembeli').classList.toggle('hidden', tab !== 'pembeli');
            document.getElementById('content-kurir').classList.toggle('hidden', tab !== 'kurir');

            // Toggle create button
            const btnKurir = document.getElementById('btn-create-kurir');
            if (tab === 'kurir') {
                btnKurir.classList.remove('hidden');
                btnKurir.classList.add('inline-flex');
            } else {
                btnKurir.classList.add('hidden');
                btnKurir.classList.remove('inline-flex');
            }

            // Toggle tab style
            document.getElementById('tab-pembeli').className = tab === 'pembeli'
                ? 'tab-btn px-6 py-2.5 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-200 bg-blue-600 text-white shadow-md shadow-blue-100'
                : 'tab-btn px-6 py-2.5 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-200 text-slate-400 hover:text-slate-700';

            document.getElementById('tab-kurir').className = tab === 'kurir'
                ? 'tab-btn px-6 py-2.5 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-200 bg-orange-500 text-white shadow-md shadow-orange-100'
                : 'tab-btn px-6 py-2.5 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-200 text-slate-400 hover:text-slate-700';

            // Update table title
            document.getElementById('table-title').textContent = tab === 'pembeli' ? 'Daftar Pembeli' : 'Daftar Kurir';
        }
    </script>
</x-app-layout>