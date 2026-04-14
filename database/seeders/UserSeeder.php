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
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'no_telp'  => '081234567890',
            ]
        );

        $this->command->info('User admin berhasil dibuat! Email: admin@gmail.com | Password: admin123');

        // ── CONTOH PEMBELI (opsional, hapus kalau tidak perlu) ──
        User::updateOrCreate(
            ['email' => 'pembeli@gmail.com'],
            [
                'name'     => 'Pembeli Test',
                'email'    => 'pembeli@gmail.com',
                'password' => Hash::make('pembeli123'),
                'role'     => 'pembeli',
                'no_telp'  => '089876543210',
            ]
        );

        $this->command->info('User pembeli berhasil dibuat! Email: pembeli@gmail.com | Password: pembeli123');

        User::updateOrCreate(
            ['email' => 'kurir@gmail.com'],
            [
                'name'     => 'Kurir Test',
                'email'    => 'kurir@gmail.com',
                'password' => Hash::make('kurir123'),
                'role'     => 'kurir',
                'no_telp'  => '08132479890',
            ]
        );

        $this->command->info('User kurir berhasil dibuat! Email: kurir@gmail.com | Password: kurir123');
    }
}
