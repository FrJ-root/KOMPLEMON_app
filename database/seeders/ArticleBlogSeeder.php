<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ArticleBlogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $categories = ['Nutrition', 'Fitness', 'Santé', 'Bien-être', 'Recettes'];
        $articles = [];
        
        for ($i = 0; $i < 20; $i++) {
            $title = $faker->sentence(rand(4, 8));
            $category = $categories[array_rand($categories)];
            $tags = implode(',', $faker->words(rand(2, 5)));
            $createdAt = $faker->dateTimeBetween('-1 year', 'now');
            
            $articles[] = [
                'titre' => $title,
                'contenu' => $faker->paragraphs(rand(3, 8), true),
                'categorie' => $category,
                'tags' => $tags,
                'statut' => rand(0, 4) > 0 ? 'publié' : 'brouillon',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }
        
        DB::table('articles_blog')->insert($articles);
    }
}
