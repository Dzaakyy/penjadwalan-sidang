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
        Schema::create('nilai_sidang_ta', function (Blueprint $table) {
            $table->bigInteger('id_nilai_sidang_ta')->primary();
            $table->bigInteger('ta_id');
            $table->double('sikap_penampilan')->nullable();
            $table->double('komunikasi_sistematika')->nullable();
            $table->double('penguasaan_materi')->nullable();
            $table->double('identifikasi_masalah')->nullable();
            $table->double('relevansi_teori')->nullable();
            $table->double('metode_algoritma')->nullable();
            $table->double('hasil_pembahasan')->nullable();
            $table->double('kesimpulan_saran')->nullable();
            $table->double('bahasa_tata_tulis')->nullable();
            $table->double('kesesuaian_fungsional')->nullable();
            $table->double('nilai_sidang')->nullable();
            $table->enum('status', ['0', '1', '2' ,'3', '4', '5'])->comment('0: Ketua, 1: Sekretaris, 2: Penguji 1, 3: Penguji 2, 4: Pembimbing 1, 5: Pembimbing 2');
        });
        Schema::table('nilai_sidang_ta', function (Blueprint $table) {
            $table->foreign('ta_id')->references('id_ta')->on('mhs_ta')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sidang_ta');
    }
};
