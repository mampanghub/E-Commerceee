<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Akun Kurir</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-2xl p-8">

                @if(session('error'))
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
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
                            class="w-full border border-gray-300 rounded-xl px-4 py-3">
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
                        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:underline text-sm">← Kembali</a>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold">
                            Buat Akun Kurir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>