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
        Schema::create('nilai_pkl', function (Blueprint $table) {
            $table->bigInteger('id_nilai_pkl')->primary();
            $table->bigInteger('mhs_pkl_id');
            $table->double('bahasa')->nullable();
            $table->double('analisis')->nullable();
            $table->double('sikap')->nullable();
            $table->double('komunikasi')->nullable();
            $table->double('penyajian')->nullable();
            $table->double('penguasaan')->nullable();
            $table->double('nilai_pkl')->nullable();
            $table->enum('status', ['0', '1'])->comment('0: Pembimbing, 1: Penguji');
        });

        Schema::table('nilai_pkl', function (Blueprint $table) {
            $table->foreign('mhs_pkl_id')->references('id_mhs_pkl')->on('mhs_pkl')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_pkl');
    }
};
