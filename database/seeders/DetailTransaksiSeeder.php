<?php

namespace Database\Seeders;

use App\Models\DetailTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach (range(1, 10) as $index) {
            $idtransaksi = DB::table('transaksi')->inRandomOrder()->value('id');
            $idbuku = DB::table('buku')->inRandomOrder()->value('id');

            DetailTransaksi::create([
                'idanggota' => $idtransaksi,
                'idpetugas' => $idbuku
            ]);
        }
    }
}
