<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
       for($i=0;$i<10;$i++)
       {
        Petugas::create([
            'nama' => $faker->name,
            'alamat' => $faker->address,
            'notelpon' => $faker->phoneNumber,
            'username' => $faker->userName,
            'password' => $faker->password
        ]);
       }
    }
}