<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id('zone_id');
            $table->string('nama_zona');
            $table->string('tipe');
            $table->decimal('harga_dasar', 10, 2);
            $table->decimal('harga_per_500gr', 10, 2)->default(2000);
            $table->integer('estimasi_hari_base')->default(2);
            $table->integer('estimasi_hari_base_max')->default(2);
            $table->integer('estimasi_hari_min')->default(1);
            $table->integer('estimasi_hari_min_max')->default(1);
            $table->timestamps();
        });

        Schema::create('shipping_options', function (Blueprint $table) {
            $table->id('option_id');
            $table->string('label');
            $table->integer('kurang_hari')->default(0);
            $table->decimal('persen_tambahan', 5, 2)->default(0);
            $table->timestamps();
        });

        // Tambah foreign key ke orders setelah shipping_zones & shipping_options sudah ada
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('zone_id')
                ->references('zone_id')
                ->on('shipping_zones')
                ->nullOnDelete();

            $table->foreign('shipping_option_id')
                ->references('option_id')
                ->on('shipping_options')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropForeign(['shipping_option_id']);
        });

        Schema::dropIfExists('shipping_options');
        Schema::dropIfExists('shipping_zones');
    }
};
