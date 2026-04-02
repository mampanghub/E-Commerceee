<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceCoordinateSeeder extends Seeder
{
    /**
     * Koordinat titik tengah tiap provinsi Indonesia.
     * province_code mengikuti kode Laravolt Indonesia.
     */
    public function run(): void
    {
        $data = [
            ['province_code' => '11', 'province_name' => 'Aceh',                           'latitude' =>   4.695135, 'longitude' =>  96.749397],
            ['province_code' => '12', 'province_name' => 'Sumatera Utara',                 'latitude' =>   2.115210, 'longitude' =>  99.534798],
            ['province_code' => '13', 'province_name' => 'Sumatera Barat',                 'latitude' =>  -0.739908, 'longitude' => 100.800129],
            ['province_code' => '14', 'province_name' => 'Riau',                           'latitude' =>   0.293461, 'longitude' => 101.706846],
            ['province_code' => '15', 'province_name' => 'Jambi',                          'latitude' =>  -1.610940, 'longitude' => 103.611748],
            ['province_code' => '16', 'province_name' => 'Sumatera Selatan',               'latitude' =>  -3.319493, 'longitude' => 103.914399],
            ['province_code' => '17', 'province_name' => 'Bengkulu',                       'latitude' =>  -3.793480, 'longitude' => 102.265869],
            ['province_code' => '18', 'province_name' => 'Lampung',                        'latitude' =>  -4.558480, 'longitude' => 105.406811],
            ['province_code' => '19', 'province_name' => 'Kepulauan Bangka Belitung',      'latitude' =>  -2.741051, 'longitude' => 106.440628],
            ['province_code' => '21', 'province_name' => 'Kepulauan Riau',                 'latitude' =>   3.942340, 'longitude' => 108.142863],
            ['province_code' => '31', 'province_name' => 'DKI Jakarta',                    'latitude' =>  -6.211544, 'longitude' => 106.845172],
            ['province_code' => '32', 'province_name' => 'Jawa Barat',                     'latitude' =>  -7.090911, 'longitude' => 107.668887],
            ['province_code' => '33', 'province_name' => 'Jawa Tengah',                    'latitude' =>  -7.150975, 'longitude' => 110.140259],
            ['province_code' => '34', 'province_name' => 'DI Yogyakarta',                  'latitude' =>  -7.879217, 'longitude' => 110.368100],
            ['province_code' => '35', 'province_name' => 'Jawa Timur',                     'latitude' =>  -7.536066, 'longitude' => 112.238623],
            ['province_code' => '36', 'province_name' => 'Banten',                         'latitude' =>  -6.405978, 'longitude' => 106.070530],
            ['province_code' => '51', 'province_name' => 'Bali',                           'latitude' =>  -8.409518, 'longitude' => 115.188919],
            ['province_code' => '52', 'province_name' => 'Nusa Tenggara Barat',            'latitude' =>  -8.652400, 'longitude' => 117.361664],
            ['province_code' => '53', 'province_name' => 'Nusa Tenggara Timur',            'latitude' =>  -8.656861, 'longitude' => 121.079796],
            ['province_code' => '61', 'province_name' => 'Kalimantan Barat',               'latitude' =>   0.128228, 'longitude' => 109.341644],
            ['province_code' => '62', 'province_name' => 'Kalimantan Tengah',              'latitude' =>  -1.681488, 'longitude' => 113.382355],
            ['province_code' => '63', 'province_name' => 'Kalimantan Selatan',             'latitude' =>  -3.093025, 'longitude' => 115.281093],
            ['province_code' => '64', 'province_name' => 'Kalimantan Timur',               'latitude' =>   0.538875, 'longitude' => 116.419389],
            ['province_code' => '65', 'province_name' => 'Kalimantan Utara',               'latitude' =>   3.073028, 'longitude' => 116.041113],
            ['province_code' => '71', 'province_name' => 'Sulawesi Utara',                 'latitude' =>   0.624858, 'longitude' => 123.975533],
            ['province_code' => '72', 'province_name' => 'Sulawesi Tengah',                'latitude' =>  -1.430619, 'longitude' => 121.445685],
            ['province_code' => '73', 'province_name' => 'Sulawesi Selatan',               'latitude' =>  -3.666491, 'longitude' => 119.974036],
            ['province_code' => '74', 'province_name' => 'Sulawesi Tenggara',              'latitude' =>  -4.145778, 'longitude' => 122.174668],
            ['province_code' => '75', 'province_name' => 'Gorontalo',                      'latitude' =>   0.548926, 'longitude' => 123.014420],
            ['province_code' => '76', 'province_name' => 'Sulawesi Barat',                 'latitude' =>  -2.840011, 'longitude' => 119.232143],
            ['province_code' => '81', 'province_name' => 'Maluku',                         'latitude' =>  -3.238945, 'longitude' => 130.145313],
            ['province_code' => '82', 'province_name' => 'Maluku Utara',                   'latitude' =>   1.570773, 'longitude' => 127.808024],
            ['province_code' => '91', 'province_name' => 'Papua Barat',                    'latitude' =>  -1.336290, 'longitude' => 133.174689],
            ['province_code' => '94', 'province_name' => 'Papua',                          'latitude' =>  -4.269928, 'longitude' => 138.080353],
        ];

        DB::table('province_coordinates')->upsert(
            $data,
            ['province_code'],
            ['province_name', 'latitude', 'longitude']
        );
    }
}
