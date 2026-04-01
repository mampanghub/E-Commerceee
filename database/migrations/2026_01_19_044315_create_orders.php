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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kurir_id')->nullable();
            $table->decimal('total_harga', 15, 2);
            $table->integer('ongkir')->default(0);
            $table->string('nomor_resi')->nullable();
            $table->string('foto_konfirmasi')->nullable();
            $table->timestamp('dikirim_at')->nullable();
            $table->string('nama_kurir')->nullable();
            $table->enum('status', [
                'dibayar',
                'dikemas',
                'dikirim',
                'selesai',
                'batal'
            ]);
            $table->string('snap_token')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('kurir_id')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
