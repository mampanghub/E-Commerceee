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
            $table->string('nama_zona');          // "Dalam Provinsi", "Luar Provinsi", "Luar Pulau"
            $table->string('tipe');               // 'dalam_provinsi', 'luar_provinsi', 'luar_pulau'
            $table->decimal('harga_dasar', 10, 2); // 15000, 25000, 45000
            $table->decimal('harga_per_500gr', 10, 2)->default(2000);
            $table->timestamps();
        });

        Schema::create('shipping_options', function (Blueprint $table) {
            $table->id('option_id');
            $table->integer('estimasi_hari');       // 1, 2, 3
            $table->string('label');                // "Express (1 Hari)", dst
            $table->decimal('persen_tambahan', 5, 2)->default(0); // 0, 25, 50
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('zone_id')->nullable()->after('ongkir');
            $table->unsignedBigInteger('shipping_option_id')->nullable()->after('zone_id');
            $table->integer('estimasi_hari')->nullable()->after('shipping_option_id');
            $table->date('estimasi_tiba')->nullable()->after('estimasi_hari');
            $table->decimal('berat_total_gram', 10, 2)->nullable()->after('estimasi_tiba');

            $table->foreign('zone_id')->references('zone_id')->on('shipping_zones')->nullOnDelete();
            $table->foreign('shipping_option_id')->references('option_id')->on('shipping_options')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropForeign(['shipping_option_id']);
            $table->dropColumn(['zone_id', 'shipping_option_id', 'estimasi_hari', 'estimasi_tiba', 'berat_total_gram']);
        });

        Schema::dropIfExists('shipping_options');
        Schema::dropIfExists('shipping_zones');
    }
};