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
        Schema::create('mhs_ta', function (Blueprint $table) {
            $table->bigInteger('id_ta')->primary();
            $table->bigInteger('mahasiswa_id');
            $table->string('proposal_final')->nullable();
            $table->string('laporan_ta')->nullable();
            $table->string('tugas_akhir')->nullable();
            $table->enum('status_berkas', ['0', '1'])->default('0')->comment('0: Belum, 1: Sudah')->nullable();

            $table->bigInteger('pembimbing_satu_id')->nullable();
            $table->bigInteger('pembimbing_dua_id')->nullable();
            $table->bigInteger('ketua')->nullable();
            $table->bigInteger('sekretaris')->nullable();
            $table->bigInteger('penguji_1')->nullable();
            $table->bigInteger('penguji_2')->nullable();

            $table->date('tanggal_ta')->nullable();
            $table->bigInteger('ruangan_id')->nullable();
            $table->bigInteger('sesi_id')->nullable();
            $table->string('nilai_mahasiswa')->nullable();
            $table->enum('keterangan', ['0', '1','2'])->default('0')->comment('0: Belum Sidang, 1:Tdk Lulus Sidang, 2:Lulus Sidang')->nullable();
            $table->enum('acc_pembimbing_satu', ['0', '1'])->default('0')->comment('0: Belum Diacc, 1:Sudah Diacc')->nullable();
            $table->enum('acc_pembimbing_dua', ['0', '1'])->default('0')->comment('0: Belum Diacc, 1:Sudah Diacc')->nullable();
        });

        Schema::table('mhs_ta', function (Blueprint $table) {
            $table->foreign('mahasiswa_id')->references('id_mahasiswa')->on('mahasiswa')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sesi_id')->references('id_sesi')->on('sesi')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ruangan_id')->references('id_ruang')->on('ruang')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pembimbing_satu_id')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pembimbing_dua_id')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ketua')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sekretaris')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('penguji_1')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('penguji_2')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mhs_ta');
    }
};
