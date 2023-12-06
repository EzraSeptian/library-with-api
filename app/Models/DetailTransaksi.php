<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = "detail_transaksi";
    protected $filltable = ['idtransaksi', 'idbuku'];
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'idtransaksi');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idbuku');
    }
}
