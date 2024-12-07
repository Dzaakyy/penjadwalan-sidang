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
        Schema::create('usulan_pkl', function (Blueprint $table) {
            $table->bigInteger('id_usulan_pkl')->primary();
            $table->bigInteger('mahasiswa_id');
            $table->bigInteger('perusahaan_id');
            // $table->string('tahun_ajaran');
            $table->enum('konfirmasi', ['0', '1'])->default(0)->comment('0: Belum, 1: Sudah');
        });

        Schema::table('usulan_pkl', function (Blueprint $table) {
            $table->foreign('mahasiswa_id')->references('id_mahasiswa')->on('mahasiswa')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('perusahaan_id')->references('id_perusahaan')->on('tempat_pkl')
                        ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_pkl');
    }
};
