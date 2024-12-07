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
        Schema::create('dosen', function (Blueprint $table) {
            $table->bigInteger('id_dosen')->primary();
            $table->string('nama_dosen');
            $table->unsignedBigInteger('user_id');
            $table->string('nidn');
            $table->string('nip');
            $table->string('gender');
            $table->bigInteger('jurusan_id');
            $table->bigInteger('prodi_id');
            $table->string('image')->nullable();
            $table->enum('golongan', ['0','1','2','3']);
            $table->enum('status_dosen', ['0', '1'])->default(1);
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jurusan_id')->references('id_jurusan')->on('jurusan')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('prodi_id')->references('id_prodi')->on('prodi')
                    ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
