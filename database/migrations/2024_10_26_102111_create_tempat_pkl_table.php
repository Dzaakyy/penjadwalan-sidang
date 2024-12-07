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
        Schema::create('tempat_pkl', function (Blueprint $table) {
            $table->bigInteger('id_perusahaan')->primary();
            $table->string('nama_perusahaan');
            $table->string('deskripsi');
            $table->integer('kuota')->default(4);
            $table->enum('status', ['0', '1'])->comment('0: Tdak Aktif, 1: Akif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_pkl');
    }
};
