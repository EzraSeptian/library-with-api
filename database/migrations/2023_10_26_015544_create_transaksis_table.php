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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idanggota');
            $table->unsignedBigInteger('idpetugas');
            $table->date('tanggalpinjam');
            $table->date('tanggalkembali')->nullable();
            $table->foreign('idanggota')->references('id')->on('anggota');
            $table->foreign('idpetugas')->references('id')->on('petugas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
