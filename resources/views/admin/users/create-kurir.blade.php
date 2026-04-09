<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-orange-500 mb-1">Admin Panel</p>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buat Akun Kurir</h1>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">

                @if (session('error'))
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store-kurir') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3" inputmode="numeric"
                            pattern="[0-9+\-\s]*" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3" required>
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:underline text-sm">←
                            Kembali</a>
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                            Buat Akun Kurir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
