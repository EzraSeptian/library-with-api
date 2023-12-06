<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = "transaksi";
    protected $filltable = ['idanggota', 'idpetugas', 'tanggalpinjam', 'tanggalkembali', 'denda'];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'idtransaksi');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'idanggota');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'idpetugas');
    }
}
