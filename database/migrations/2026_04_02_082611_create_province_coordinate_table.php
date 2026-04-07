<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('province_coordinates', function (Blueprint $table) {
            $table->id();
            $table->string('province_code', 10)->unique();
            $table->string('province_name');
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('province_coordinates');
    }
};
