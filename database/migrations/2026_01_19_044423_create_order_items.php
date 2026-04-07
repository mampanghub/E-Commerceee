<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('harga', 15, 2);
            $table->integer('jumlah');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->restrictOnDelete();

            $table->foreign('variant_id')
                ->references('variant_id')
                ->on('product_variants')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
