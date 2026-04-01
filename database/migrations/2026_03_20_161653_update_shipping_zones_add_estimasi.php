<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom estimasi ke shipping_zones
        Schema::table('shipping_zones', function (Blueprint $table) {
            $table->integer('estimasi_hari_base')->default(2)->after('harga_per_500gr');
            $table->integer('estimasi_hari_min')->default(1)->after('estimasi_hari_base');
        });

        // Ganti estimasi_hari jadi kurang_hari di shipping_options
        Schema::table('shipping_options', function (Blueprint $table) {
            $table->dropColumn('estimasi_hari');
            $table->integer('kurang_hari')->default(0)->after('label');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            $table->dropColumn(['estimasi_hari_base', 'estimasi_hari_min']);
        });

        Schema::table('shipping_options', function (Blueprint $table) {
            $table->dropColumn('kurang_hari');
            $table->integer('estimasi_hari')->default(3)->after('label');
        });
    }
};