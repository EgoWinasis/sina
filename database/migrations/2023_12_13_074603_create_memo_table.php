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
        Schema::create('memo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('marketing',255);
            // debitur
            $table->string('nama_debitur',255);
            $table->string('nik_debitur',20);
            $table->string('tempat_lahir_debitur',255);
            $table->string('tgl_lahir_debitur',20);
            $table->string('alamat_debitur',255);
            $table->json('file_debitur')->nullable;
            // penjamin
            $table->string('nama_penjamin',255)->nullable;
            $table->string('nik_penjamin',20)->nullable;
            $table->string('tempat_lahir_penjamin',255)->nullable;
            $table->string('tgl_lahir_penjamin',20)->nullable;
            $table->string('alamat_penjamin',255)->nullable;
            $table->json('file_penjamin')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo');
    }
};
