<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->decimal('value', 12, 2);
            $table->string('label');
            $table->string('satuan')->nullable(); // 'Rp/km', 'Rp/500gr', 'km'
            $table->timestamps();
        });

        // Seed nilai default
        DB::table('shipping_settings')->insert([
            ['key' => 'tarif_per_km',    'value' => 1500, 'label' => 'Tarif per Kilometer',       'satuan' => 'Rp/km',    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tarif_per_500gr', 'value' => 500,  'label' => 'Tarif per 500gr Tambahan',  'satuan' => 'Rp/500gr', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'jarak_minimum',   'value' => 5,    'label' => 'Jarak Minimum Pengiriman',  'satuan' => 'km',       'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_settings');
    }
};
