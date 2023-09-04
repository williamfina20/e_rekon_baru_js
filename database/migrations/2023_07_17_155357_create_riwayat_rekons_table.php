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
        Schema::create('riwayat_rekons', function (Blueprint $table) {
            $table->id();
            $table->string('rekons_id')->nullable();
            $table->string('proses')->nullable();
            $table->longText('riwayat_ubah')->nullable();
            $table->string('akun_tipe')->nullable();
            $table->string('akun_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_rekons');
    }
};
