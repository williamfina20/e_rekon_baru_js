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
        Schema::create('maskapai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pimpinan')->nullable();
            $table->string('jabatan_pimpinan')->nullable();
            $table->string('bandara_id')->nullable();
            $table->string('users_id')->nullable();
            $table->string('maskapai_pusat_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maskapais');
    }
};
