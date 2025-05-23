<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TemoignageSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $testimonials = [];
        
        for ($i = 0; $i < 15; $i++) {
            $testimonials[] = [
                'nom_client' => $faker->name,
                'contenu' => $faker->paragraph(rand(2, 5)),
                'media_url' => rand(0, 1) ? 'testimonials/client_' . ($i+1) . '.jpg' : null,
                'statut' => rand(0, 3) > 0 ? 'approuvé' : 'en attente', // 75% approved, 25% pending
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ];
        }
        
        DB::table('temoignages')->insert($testimonials);
    }
}
