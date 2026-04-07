<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('order_id');
            $table->string('metode_pembayaran');
            $table->enum('status', ['pending', 'berhasil', 'gagal']);
            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
