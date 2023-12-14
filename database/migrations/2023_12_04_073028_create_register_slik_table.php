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
        Schema::create('register_slik', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_ref',100)->unique();
            $table->date('tgl_permintaan');
            $table->string('nik',20);
            $table->string('tujuan_permintaan',3);
            $table->string('nama',255);
            $table->date('tgl_lahir');
            $table->string('tempat_lahir',255);
            $table->string('petugas',3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_slik');
    }
};
