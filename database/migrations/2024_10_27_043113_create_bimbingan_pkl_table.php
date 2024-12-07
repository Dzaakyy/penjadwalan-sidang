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
        Schema::create('bimbingan_pkl', function (Blueprint $table) {
            $table->bigInteger('id_bimbingan_pkl')->primary();
            $table->bigInteger('pkl_id');
            $table->text('kegiatan');
            $table->date('tgl_kegiatan_awal');
            $table->date('tgl_kegiatan_akhir');
            $table->string('file_dokumentasi')->nullable();
            $table->text('komentar')->nullable();
            $table->enum('status', ['0', '1'])->default(0)->comment('0: Belum Diverifikasi, 1: Diverifikasi');
        });

        Schema::table('bimbingan_pkl', function (Blueprint $table) {
            $table->foreign('pkl_id')->references('id_mhs_pkl')->on('mhs_pkl')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingan_pkl', function (Blueprint $table) {
            $table->dropForeign(['pkl_id']);
        });

        Schema::dropIfExists('bimbingan_pkl');
    }
};
