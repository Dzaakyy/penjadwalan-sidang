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
        Schema::create('prodi', function (Blueprint $table) {
            $table->bigInteger('id_prodi')->primary();
            $table->string('kode_prodi');
            $table->string('prodi');
            $table->string('jenjang');
            $table->bigInteger('jurusan_id');
        });

        Schema::table('prodi', function (Blueprint $table) {
            $table->foreign('jurusan_id')->references('id_jurusan')->on('jurusan')
                    ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};
