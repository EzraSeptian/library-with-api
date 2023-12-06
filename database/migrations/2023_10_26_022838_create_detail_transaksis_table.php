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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('idtransaksi');
            $table->unsignedBigInteger('idbuku');
            $table->primary(['idtransaksi', 'idbuku']);
            $table->foreign('idtransaksi')->references('id')->on('transaksi');
            $table->foreign('idbuku')->references('id')->on('buku');
            $table->float('denda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
