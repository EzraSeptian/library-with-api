<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = \Faker\Factory::create('id_ID');
       for($i=0;$i<10;$i++)
       {
        Anggota::create([
            'nama' => $faker->name,
            'alamat' => $faker->address,
            'jurusan' => $faker->word,
            'username' => $faker->userName,
            'password' => $faker->password
        ]);
       }
    }
}