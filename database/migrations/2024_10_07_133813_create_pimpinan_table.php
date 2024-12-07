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
        Schema::create('pimpinan', function (Blueprint $table) {
            $table->bigInteger('id_pimpinan')->primary();
            $table->bigInteger('dosen_id');
            $table->bigInteger('jabatan_pimpinan_id');
            $table->string('periode');
            $table->enum('status_pimpinan', ['0', '1'])->default(1);
        });

        Schema::table('pimpinan', function (Blueprint $table) {
            $table->foreign('dosen_id')->references('id_dosen')->on('dosen')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jabatan_pimpinan_id')->references('id_jabatan_pimpinan')->on('jabatan_pimpinan')
                    ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pimpinan_jurusan');
    }
};
