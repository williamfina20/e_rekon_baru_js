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
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('rekons_id')->nullable('');
            $table->string('maskapai_nama_pimpinan')->nullable();
            $table->string('maskapai_jabatan_pimpinan')->nullable();
            $table->string('bandara_nama_pimpinan')->nullable();
            $table->string('bandara_jabatan_pimpinan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};
