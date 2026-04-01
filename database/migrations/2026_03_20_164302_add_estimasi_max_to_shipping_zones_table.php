<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            // max untuk range atas estimasi (misal base min=4, base max=5 → "4-5 hari")
            $table->integer('estimasi_hari_base_max')->default(2)->after('estimasi_hari_base');
            $table->integer('estimasi_hari_min_max')->default(1)->after('estimasi_hari_min');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            $table->dropColumn(['estimasi_hari_base_max', 'estimasi_hari_min_max']);
        });
    }
};