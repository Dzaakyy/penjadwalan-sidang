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
            $table->double('pendahuluan')->nullable();
            $table->double('tinjauan_pustaka')->nullable();
            $table->double('metodologi')->nullable();
            $table->double('penggunaan_bahasa')->nullable();
            $table->double('presentasi')->nullable();
            $table->double('nilai_sidang')->nullable();
            $table->enum('status', ['0', '1', '2' ,'3'])->comment('0: Ketua, 1: Sekretaris, 3: Penguji 1, 4: Penguji 2');
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
