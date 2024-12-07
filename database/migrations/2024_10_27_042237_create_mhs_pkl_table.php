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
        Schema::create('mhs_pkl', function (Blueprint $table) {
            $table->bigInteger('id_mhs_pkl')->primary();
            $table->bigInteger('mahasiswa_id');
            $table->text('judul')->nullable();
            $table->string('pembimbing_pkl')->nullable();
            $table->string('tahun_pkl');
            $table->string('dokumen_nilai_industri')->nullable();
            $table->double('nilai_pembimbing_industri')->nullable();
            $table->string('laporan_pkl')->nullable();
            $table->enum('status_admin', ['0', '1'])->default(0)->comment('0: Belum Diverifikasi, 1: Diverifikasi');
            $table->bigInteger('ruang_sidang')->nullable();
            $table->bigInteger('dosen_pembimbing');
            $table->double('nilai_dosen_pembimbing')->nullable();
            $table->bigInteger('dosen_penguji')->nullable();
            $table->bigInteger('jam_sidang')->nullable();
            $table->date('tgl_sidang')->nullable();
        });

        Schema::table('mhs_pkl', function (Blueprint $table) {
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('usulan_pkl')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dosen_pembimbing')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dosen_penguji')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ruang_sidang')->references('id_ruang')->on('ruang')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jam_sidang')->references('id_sesi')->on('sesi')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mhs_pkl');
    }
};
