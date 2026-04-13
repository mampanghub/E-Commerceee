<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Ubah semua kolom wilayah dari unsignedBigInteger ke string
            // supaya konsisten dengan laravolt/indonesia yang pakai kode string
            $table->string('province_id')->nullable()->change();
            $table->string('city_id')->nullable()->change();
            $table->string('district_id')->nullable()->change();
            // village_id sudah string dari awal, tidak perlu diubah
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable()->change();
            $table->unsignedBigInteger('city_id')->nullable()->change();
            $table->unsignedBigInteger('district_id')->nullable()->change();
        });
    }
};
