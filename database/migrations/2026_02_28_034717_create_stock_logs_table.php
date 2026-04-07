<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('variant_id');
            $table->integer('stok_lama');
            $table->integer('stok_baru');
            $table->integer('jumlah');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('variant_id')
                ->references('variant_id')
                ->on('product_variants')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
