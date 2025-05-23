<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $promotions = [];
        
        for ($i = 0; $i < 10; $i++) {
            $startDate = $faker->dateTimeBetween('-1 month', '+2 months');
            $endDate = clone $startDate;
            $endDate->modify('+' . rand(7, 60) . ' days');
            
            $type = rand(0, 1) === 0 ? 'pourcentage' : 'montant';
            $value = $type === 'pourcentage' ? rand(5, 50) : rand(500, 3000) / 100;
            
            $promotions[] = [
                'code' => strtoupper(Str::random(8)),
                'type' => $type,
                'valeur' => $value,
                'date_debut' => $startDate,
                'date_fin' => $endDate,
                'utilisation_unique' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('promotions')->insert($promotions);
    }
}
