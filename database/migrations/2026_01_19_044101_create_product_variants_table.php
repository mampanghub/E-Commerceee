<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('variant_id');
            $table->unsignedBigInteger('product_id');
            $table->string('nama_varian'); // Contoh: "Biru, XL" atau "Merah"
            $table->integer('stok')->default(0);
            $table->integer('berat')->default(0);
            $table->decimal('harga_tambahan', 12, 2)->default(0); // Kalau warna merah lebih mahal, misal.
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
