<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('shipping_zones')->truncate();
        DB::table('shipping_options')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('shipping_zones')->insert([
            [
                'nama_zona'               => 'Dalam Provinsi',
                'tipe'                    => 'dalam_provinsi',
                'harga_dasar'             => 15000,
                'harga_per_500gr'         => 2000,
                'estimasi_hari_base'      => 2,   // min
                'estimasi_hari_base_max'  => 3,   // max → "2-3 hari"
                'estimasi_hari_min'       => 1,
                'estimasi_hari_min_max'   => 1,   // batas bawah express → "1 hari"
                'created_at'              => now(),
                'updated_at'              => now(),
            ],
            [
                'nama_zona'               => 'Luar Provinsi',
                'tipe'                    => 'luar_provinsi',
                'harga_dasar'             => 25000,
                'harga_per_500gr'         => 2000,
                'estimasi_hari_base'      => 4,   // min
                'estimasi_hari_base_max'  => 5,   // max → "4-5 hari"
                'estimasi_hari_min'       => 2,
                'estimasi_hari_min_max'   => 3,   // batas bawah express → "2-3 hari"
                'created_at'              => now(),
                'updated_at'              => now(),
            ],
            [
                'nama_zona'               => 'Luar Pulau',
                'tipe'                    => 'luar_pulau',
                'harga_dasar'             => 45000,
                'harga_per_500gr'         => 2000,
                'estimasi_hari_base'      => 8,   // min
                'estimasi_hari_base_max'  => 10,  // max → "8-10 hari"
                'estimasi_hari_min'       => 4,
                'estimasi_hari_min_max'   => 6,   // batas bawah express → "4-6 hari"
                'created_at'              => now(),
                'updated_at'              => now(),
            ],
        ]);

        DB::table('shipping_options')->insert([
            [
                'label'           => 'Reguler',
                'kurang_hari'     => 0,
                'persen_tambahan' => 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'label'           => 'Cepat',
                'kurang_hari'     => 1,
                'persen_tambahan' => 25,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'label'           => 'Express',
                'kurang_hari'     => 2,
                'persen_tambahan' => 50,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}