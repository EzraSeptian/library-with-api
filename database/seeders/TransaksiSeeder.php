<?php

namespace Database\Seeders;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        foreach (range(1, 10) as $index) {
            $idAnggota = DB::table('anggota')->inRandomOrder()->value('id');
            $idPetugas = DB::table('petugas')->inRandomOrder()->value('id');
            $idBuku = DB::table('buku')->inRandomOrder()->value('id');

            // Buat entri di tabel transaksi
            $transaksiId = DB::table('transaksi')->insertGetId([
                'idanggota' => $idAnggota,
                'idpetugas' => $idPetugas,
                'tanggalpinjam' => $faker->date,
                'tanggalkembali' => $faker->date,
            ]);

            // Buat entri di tabel detailtransaksi
            DB::table('detail_transaksi')->insert([
                'idtransaksi' => $transaksiId,
                'idbuku' => $idBuku,
            ]);
        }
    }
}
