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
        Schema::create('nilai_sempro', function (Blueprint $table) {
            $table->bigInteger('id_nilai_sempro')->primary();
            $table->bigInteger('sempro_id');
            $table->double('pendahuluan')->nullable();
            $table->double('tinjauan_pustaka')->nullable();
            $table->double('metodologi')->nullable();
            $table->double('penggunaan_bahasa')->nullable();
            $table->double('presentasi')->nullable();
            $table->double('nilai_sempro')->nullable();
            $table->enum('status', ['0', '1', '2'])->comment('0: Pembimbing Satu, 1: Pembimbing Dua, 3: Penguji');
        });
        Schema::table('nilai_sempro', function (Blueprint $table) {
            $table->foreign('sempro_id')->references('id_sempro')->on('mhs_sempro')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sempro');
    }
};
