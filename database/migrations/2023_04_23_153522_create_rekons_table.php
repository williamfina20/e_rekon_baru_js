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
        Schema::create('rekons', function (Blueprint $table) {
            $table->id();
            $table->string('bulan')->nullable();
            $table->string('admin_acc')->nullable();
            $table->string('maskapai_acc')->nullable();
            $table->string('bandara_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekons');
    }
};
