<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $clients = [];
        
        for ($i = 0; $i < 10; $i++) {
            $clients[] = [
                'nom' => $faker->name,
                'adresse' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'telephone' => $faker->phoneNumber,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ];
        }
        
        DB::table('clients')->insert($clients);
    }
}