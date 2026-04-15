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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->decimal('saldo', 12, 2)->default(0);
            $table->string('no_telp', 20)->nullable();
            $table->string('password');
            $table->rememberToken()->nullable();
            $table->enum('role', ['admin', 'pembeli', 'kurir'])->default('pembeli');
            $table->string('province_id')->nullable();
            $table->string('city_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('village_id')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
