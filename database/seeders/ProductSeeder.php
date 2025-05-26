<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        
        $products = [];
        
        for ($i = 1; $i <= 30; $i++) {
            $categoryId = $categoryIds[array_rand($categoryIds)];
            $prix = rand(1000, 10000) / 100;
            $prixPromo = rand(0, 1) ? (rand(500, $prix*100 - 100) / 100) : null;

            $ingredients = json_encode([
                ['name' => 'Ingredient ' . $i . '-1', 'quantity' => rand(10, 100) . 'mg'],
                ['name' => 'Ingredient ' . $i . '-2', 'quantity' => rand(10, 100) . 'mg'],
                ['name' => 'Ingredient ' . $i . '-3', 'quantity' => rand(10, 100) . 'mg'],
            ]);
            
            $nutritionalValues = json_encode([
                ['nutrient' => 'Protéines', 'value' => rand(5, 30), 'unit' => 'g'],
                ['nutrient' => 'Glucides', 'value' => rand(2, 50), 'unit' => 'g'],
                ['nutrient' => 'Lipides', 'value' => rand(1, 20), 'unit' => 'g'],
            ]);

            $products[] = [
                'nom' => 'Produit ' . $i . ' ' . Str::random(5),
                'description' => 'Description détaillée du produit ' . $i . '. Ce produit offre de nombreux bienfaits pour votre santé et bien-être quotidien.',
                'categorie_id' => $categoryId,
                'prix' => $prix,
                'prix_promo' => $prixPromo,
                'stock' => rand(0, 100),
                'ingredients' => $ingredients,
                'valeurs_nutritionnelles' => $nutritionalValues,
                'statut' => rand(0, 3) > 0 ? 'publié' : 'brouillon',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('produits')->insert($products);
        
        $productIds = DB::table('produits')->pluck('id')->toArray();
        $mediaEntries = [];
        
        foreach ($productIds as $productId) {
            $imageCount = rand(1, 4);
            
            for ($j = 1; $j <= $imageCount; $j++) {
                $mediaEntries[] = [
                    'produit_id' => $productId,
                    'url' => 'produits/produit_' . $productId . '_' . $j . '.jpg',
                    'type' => 'image',
                    'created_at' => now(),
                ];
            }
            
            if (rand(0, 4) === 0) {
                $mediaEntries[] = [
                    'produit_id' => $productId,
                    'url' => 'produits/produit_' . $productId . '_video.mp4',
                    'type' => 'video',
                    'created_at' => now(),
                ];
            }
        }
        
        DB::table('medias_produits')->insert($mediaEntries);
    }
}
