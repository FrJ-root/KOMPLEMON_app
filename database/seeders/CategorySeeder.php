<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder{
    public function run(){
        $categories = [
            [
                'nom' => 'Vitamines',
                'description' => 'Tous nos compléments en vitamines pour votre santé quotidienne',
                'image_url' => 'categories/vitamines.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Minéraux',
                'description' => 'Compléments en minéraux essentiels pour l\'équilibre de votre corps',
                'image_url' => 'categories/mineraux.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Nutrition Sportive',
                'description' => 'Produits spécifiques pour les sportifs et l\'optimisation de la performance',
                'image_url' => 'categories/sport.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Protéines',
                'description' => 'Gamme complète de protéines végétales et animales pour vos besoins quotidiens',
                'image_url' => 'categories/proteines.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Bien-être',
                'description' => 'Produits naturels pour améliorer votre bien-être général',
                'image_url' => 'categories/bien-etre.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}