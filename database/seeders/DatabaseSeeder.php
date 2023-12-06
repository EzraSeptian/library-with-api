<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
       for($i=0;$i<10;$i++)
       {
        Anggota::create([
            'nama' => $faker->name,
            'alamat' => $faker->word,
            'jurusan' => $faker->date,
            'username' => $faker->userName,
            'password' => $faker->password
        ]);
       }
    }
}