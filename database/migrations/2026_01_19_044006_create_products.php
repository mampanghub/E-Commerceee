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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->unsignedBigInteger('store_id')->default(1);
            $table->unsignedBigInteger('category_id');
            $table->string('nama_produk');
            $table->text('deskripsi');
            $table->decimal('harga', 15, 2);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('spesifikasi')->nullable();
            $table->timestamps();

            $table->foreign('store_id')
                ->references('store_id')
                ->on('stores')
                ->cascadeOnDelete();

            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
