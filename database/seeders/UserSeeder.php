<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── ADMIN ──────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@mampangpedia.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@mampangpedia.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'no_telp'  => '081234567890',
            ]
        );

        $this->command->info('User admin berhasil dibuat! Email: admin@mampangpedia.com | Password: admin123');

        // ── CONTOH PEMBELI (opsional, hapus kalau tidak perlu) ──
        User::updateOrCreate(
            ['email' => 'pembeli@mampangpedia.com'],
            [
                'name'     => 'Pembeli Test',
                'email'    => 'pembeli@mampangpedia.com',
                'password' => Hash::make('pembeli123'),
                'role'     => 'pembeli',
                'no_telp'  => '089876543210',
            ]
        );

        $this->command->info('User pembeli berhasil dibuat! Email: pembeli@mampangpedia.com | Password: pembeli123');
    }
}