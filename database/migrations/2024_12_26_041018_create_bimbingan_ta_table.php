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
        Schema::create('bimbingan_ta', function (Blueprint $table) {
            $table->bigInteger('id_bimbingan_ta')->primary();
            $table->bigInteger('ta_id');
            $table->bigInteger('dosen_id');
            $table->text('pembahasan');
            $table->date('tgl_bimbingan');
            $table->string('file_bimbingan')->nullable();
            $table->text('komentar')->nullable();
            $table->enum('status', ['0', '1'])->default(0)->comment('0: Belum Diverifikasi, 1: Diverifikasi');
        });

        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->foreign('ta_id')->references('id_ta')->on('mhs_ta')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id_dosen')->on('dosen')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan_ta');
    }
};
