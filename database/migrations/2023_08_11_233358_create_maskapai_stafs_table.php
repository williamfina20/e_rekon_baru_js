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
        Schema::create('maskapai_staf', function (Blueprint $table) {
            $table->id();
            $table->string('maskapai_id')->nullable();
            $table->string('users_id')->nullable();
            $table->string('jabatan_staf')->nullable();
            $table->string('kode_jabatan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maskapai_stafs');
    }
};
