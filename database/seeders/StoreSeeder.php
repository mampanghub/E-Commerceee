<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user_id admin pertama yang ditemukan
        $admin = DB::table('users')->where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn('Tidak ada user dengan role admin. Seeder dilewati.');
            return;
        }

        // Cek apakah admin sudah punya toko
        $sudahAda = DB::table('stores')->where('user_id', $admin->user_id)->exists();

        if ($sudahAda) {
            $this->command->info('Admin sudah punya toko. Seeder dilewati.');
            return;
        }

        DB::table('stores')->insert([
    'user_id'     => $admin->user_id,
    'nama_toko'   => 'Mampang Pedia',
    'deskripsi'   => 'Toko resmi Mampang Pedia. Menjual berbagai produk pilihan dengan harga terbaik.',
    'province_id' => 32,
    'city_id'     => 3276,   // Kota Depok
    'district_id' => 3276010, // sesuaikan dengan data di tabel districts
    'village_id'  => '3276010001', // sesuaikan dengan data di tabel villages
    'alamat'      => 'Jl. Margonda Raya, Depok, Jawa Barat',
    'status'      => 'active',
    'saldo'       => 0,
    'created_at'  => now(),
    'updated_at'  => now(),
]);
        $this->command->info('Store admin berhasil dibuat!');
    }
}
