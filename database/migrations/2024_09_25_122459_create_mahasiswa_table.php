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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->bigInteger('id_mahasiswa')->primary();
            $table->string('nama');
            $table->unsignedBigInteger('user_id');
            $table->string('nim');
            $table->bigInteger('prodi_id');
            $table->string('image')->nullable();
            $table->enum('gender', ['0', '1']);
            $table->enum('status_mahasiswa', ['0', '1'])->default(1);
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('mahasiswa');
    }
};
