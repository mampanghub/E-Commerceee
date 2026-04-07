<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kurir_id')->nullable();
            $table->decimal('total_harga', 15, 2);
            $table->integer('ongkir')->default(0);
            $table->string('shipping_address')->nullable();
            $table->text('catatan')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('no_telp_penerima')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('shipping_option_id')->nullable();
            $table->integer('estimasi_hari')->nullable();
            $table->date('estimasi_tiba')->nullable();
            $table->date('estimasi_tiba_max')->nullable();
            $table->decimal('berat_total_gram', 10, 2)->nullable();
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

            // zone_id & shipping_option_id foreign keys ada di create_shipping_zone_table
            // karena shipping_zones dibuat setelah orders
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
