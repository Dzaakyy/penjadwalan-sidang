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
        Schema::create('nilai_bimbingan_pkl', function (Blueprint $table) {
            $table->bigInteger('id_nilai_bimbingan_pkl')->primary();
            $table->bigInteger('mhs_pkl_id');
            $table->double('keaktifan')->nullable();
            $table->double('komunikatif')->nullable();
            $table->double('problem_solving')->nullable();
            $table->double('nilai_bimbingan')->nullable();
        });

        Schema::table('nilai_bimbingan_pkl', function (Blueprint $table) {
            $table->foreign('mhs_pkl_id')->references('id_mhs_pkl')->on('mhs_pkl')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_bimbingan_pkl');
    }
};
