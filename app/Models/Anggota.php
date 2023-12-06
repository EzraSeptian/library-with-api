<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = "anggota";
    protected $filltable = ['nama', 'alamat', 'jurusan', 'email', 'password'];

    public function getAllData()
    {
        return $this->all();
    }
}
