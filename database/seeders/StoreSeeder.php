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
            'status'      => 'active',
            'saldo'       => 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->command->info('Store admin berhasil dibuat!');
    }
}