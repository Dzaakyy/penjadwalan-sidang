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
        Schema::create('mhs_sempro', function (Blueprint $table) {
            $table->bigInteger('id_sempro')->primary();
            $table->bigInteger('mahasiswa_id');
            $table->text('judul');
            $table->string('file_sempro');
            $table->bigInteger('pembimbing_satu')->nullable();
            $table->bigInteger('pembimbing_dua')->nullable();
            $table->bigInteger('penguji')->nullable();
            $table->date('tanggal_sempro')->nullable();
            $table->bigInteger('ruangan_id')->nullable();
            $table->bigInteger('sesi_id')->nullable();
            $table->string('nilai_mahasiswa')->nullable();
            $table->enum('status_judul', ['0', '1','2'])->default('0')->comment('0: Belum, 1: Ditolak, 2:Diterima')->nullable();
            $table->enum('status_berkas', ['0', '1'])->default('0')->comment('0: Belum, 1: Sudah')->nullable();
            $table->enum('keterangan', ['0', '1','2'])->default('0')->comment('0: Belum, 1: Lulus, 2: Tidak Lulus')->nullable();
        });

        Schema::table('mhs_sempro', function (Blueprint $table) {
            $table->foreign('mahasiswa_id')->references('id_mahasiswa')->on('mahasiswa')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sesi_id')->references('id_sesi')->on('sesi')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ruangan_id')->references('id_ruang')->on('ruang')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pembimbing_satu')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pembimbing_dua')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('penguji')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mhs_sempro');
    }
};
