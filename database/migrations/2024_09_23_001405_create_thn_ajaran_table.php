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
        Schema::create('thn_ajaran', function (Blueprint $table) {
            $table->bigInteger('id_thn_ajaran')->primary();
            $table->string('thn_ajaran');
            $table->enum('status', ['0', '1'])->default(null)->comment('0: Tidak Aktif, 1: Aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thn_ajaran');
    }
};
