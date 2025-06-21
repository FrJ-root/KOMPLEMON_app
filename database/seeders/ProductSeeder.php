<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('products');
        
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        
        $this->copySampleImages();
        
        $products = [
            [
                'nom' => 'KOMPLEMON Oméga-3 Premium',
                'description' => 'Notre formule d\'huile de poisson de haute qualité contient des acides gras essentiels EPA et DHA qui soutiennent la santé cardiovasculaire et cérébrale. Extraction par méthode froide pour préserver l\'intégrité nutritionnelle.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Bien-être'),
                'prix' => 24.99,
                'prix_promo' => 19.99,
                'stock' => rand(20, 100),
                'image' => 'products/omega3.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Huile de poisson concentrée', 'quantity' => '1000mg'],
                    ['name' => 'EPA (acide eicosapentaénoïque)', 'quantity' => '180mg'],
                    ['name' => 'DHA (acide docosahexaénoïque)', 'quantity' => '120mg'],
                    ['name' => 'Vitamine E (antioxydant)', 'quantity' => '5mg'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Calories', 'value' => 10, 'unit' => 'kcal'],
                    ['nutrient' => 'Lipides totaux', 'value' => 1, 'unit' => 'g'],
                    ['nutrient' => 'Acides gras oméga-3', 'value' => 300, 'unit' => 'mg'],
                ]),
                'statut' => 'publié',
                'featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Protéine Végétale Bio',
                'description' => 'Mélange de protéines végétales biologiques à base de pois, riz et chanvre. Idéal pour les végétariens et végétaliens souhaitant augmenter leur apport protéique quotidien. Sans additifs artificiels, arômes naturels.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Protéines'),
                'prix' => 32.50,
                'prix_promo' => null,
                'stock' => rand(5, 50),
                'image' => 'products/protein.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Protéine de pois isolée biologique', 'quantity' => '15g'],
                    ['name' => 'Protéine de riz brun biologique', 'quantity' => '10g'],
                    ['name' => 'Protéine de chanvre biologique', 'quantity' => '5g'],
                    ['name' => 'Arôme naturel de vanille', 'quantity' => '0.5g'],
                    ['name' => 'Gomme de xanthane', 'quantity' => '0.2g'],
                    ['name' => 'Stévia', 'quantity' => '0.1g'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Calories', 'value' => 120, 'unit' => 'kcal'],
                    ['nutrient' => 'Protéines', 'value' => 25, 'unit' => 'g'],
                    ['nutrient' => 'Glucides', 'value' => 2, 'unit' => 'g'],
                    ['nutrient' => 'Lipides', 'value' => 1.5, 'unit' => 'g'],
                    ['nutrient' => 'Fibres', 'value' => 1, 'unit' => 'g'],
                ]),
                'statut' => 'publié',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Complexe Vitamine B',
                'description' => 'Formule complète de vitamines B essentielles pour soutenir le métabolisme énergétique, la fonction nerveuse et la santé cognitive. Inclut B1, B2, B3, B5, B6, B7, B9 et B12 dans leurs formes les plus biodisponibles.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Vitamines'),
                'prix' => 19.95,
                'prix_promo' => 16.95,
                'stock' => rand(30, 80),
                'image' => 'products/vitamin-b.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Vitamine B1 (Thiamine)', 'quantity' => '50mg'],
                    ['name' => 'Vitamine B2 (Riboflavine)', 'quantity' => '50mg'],
                    ['name' => 'Vitamine B3 (Niacine)', 'quantity' => '50mg'],
                    ['name' => 'Vitamine B5 (Acide pantothénique)', 'quantity' => '50mg'],
                    ['name' => 'Vitamine B6 (Pyridoxine)', 'quantity' => '50mg'],
                    ['name' => 'Vitamine B7 (Biotine)', 'quantity' => '300μg'],
                    ['name' => 'Vitamine B9 (Acide folique)', 'quantity' => '400μg'],
                    ['name' => 'Vitamine B12 (Méthylcobalamine)', 'quantity' => '500μg'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Calories', 'value' => 5, 'unit' => 'kcal'],
                    ['nutrient' => 'Glucides', 'value' => 1, 'unit' => 'g'],
                ]),
                'statut' => 'publié',
                'featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Magnésium Bisglycinate',
                'description' => 'Forme hautement absorbable de magnésium, idéale pour réduire la fatigue, soutenir la fonction musculaire et le système nerveux. Notre formule bisglycinate est douce pour l\'estomac et ne provoque pas les effets laxatifs communs avec d\'autres formes de magnésium.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Minéraux'),
                'prix' => 22.99,
                'prix_promo' => null,
                'stock' => rand(15, 60),
                'image' => 'products/magnesium.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Magnésium bisglycinate', 'quantity' => '200mg'],
                    ['name' => 'Cellulose microcristalline', 'quantity' => '50mg'],
                    ['name' => 'Stéarate de magnésium végétal', 'quantity' => '5mg'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Magnésium élémentaire', 'value' => 200, 'unit' => 'mg'],
                ]),
                'statut' => 'publié',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Créatine Monohydrate Pure',
                'description' => 'Créatine monohydrate micronisée de la plus haute qualité, conçue pour améliorer les performances sportives, augmenter la force et favoriser la récupération musculaire. Sans additifs, sans saveur, facilement soluble.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Nutrition Sportive'),
                'prix' => 29.99,
                'prix_promo' => 24.99,
                'stock' => rand(40, 100),
                'image' => 'products/creatine.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Créatine monohydrate micronisée', 'quantity' => '5g'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Créatine monohydrate', 'value' => 5, 'unit' => 'g'],
                    ['nutrient' => 'Calories', 'value' => 0, 'unit' => 'kcal'],
                ]),
                'statut' => 'publié',
                'featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Vitamine D3+K2 Gouttes',
                'description' => 'Combinaison synergique de vitamines D3 et K2 (MK-7) pour une santé osseuse optimale et une meilleure absorption du calcium. Formule en gouttes à base d\'huile d\'olive pour une absorption maximale.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Vitamines'),
                'prix' => 24.50,
                'prix_promo' => null,
                'stock' => rand(10, 50),
                'image' => 'products/vitamin-d.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Vitamine D3 (cholécalciférol)', 'quantity' => '1000UI'],
                    ['name' => 'Vitamine K2 (MK-7)', 'quantity' => '100μg'],
                    ['name' => 'Huile d\'olive extra vierge biologique', 'quantity' => 'qsp'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Vitamine D3', 'value' => 25, 'unit' => 'μg (1000UI)'],
                    ['nutrient' => 'Vitamine K2', 'value' => 100, 'unit' => 'μg'],
                    ['nutrient' => 'Calories', 'value' => 4, 'unit' => 'kcal'],
                ]),
                'statut' => 'publié',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Collagène Marin Hydrolysé',
                'description' => 'Peptides de collagène marin de type I, hautement biodisponibles, pour soutenir la santé de la peau, des cheveux, des ongles et des articulations. Sans saveur, se dissout facilement dans les boissons chaudes ou froides.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Bien-être'),
                'prix' => 34.99,
                'prix_promo' => 29.99,
                'stock' => rand(25, 75),
                'image' => 'products/collagen.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Collagène marin hydrolysé (poisson)', 'quantity' => '10g'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Protéines', 'value' => 9, 'unit' => 'g'],
                    ['nutrient' => 'Calories', 'value' => 35, 'unit' => 'kcal'],
                ]),
                'statut' => 'publié',
                'featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Complexe Minéraux Essentiels',
                'description' => 'Formule complète de minéraux essentiels comprenant zinc, sélénium, cuivre, manganèse et chrome. Conçu pour combler les carences nutritionnelles et soutenir diverses fonctions métaboliques et immunitaires.',
                'categorie_id' => $this->getCategoryId($categoryIds, 'Minéraux'),
                'prix' => 19.99,
                'prix_promo' => null,
                'stock' => rand(30, 70),
                'image' => 'products/minerals.jpg',
                'ingredients' => json_encode([
                    ['name' => 'Zinc (citrate)', 'quantity' => '15mg'],
                    ['name' => 'Sélénium (sélénométhionine)', 'quantity' => '100μg'],
                    ['name' => 'Cuivre (glycinate)', 'quantity' => '1mg'],
                    ['name' => 'Manganèse (glycinate)', 'quantity' => '2mg'],
                    ['name' => 'Chrome (picolinate)', 'quantity' => '100μg'],
                ]),
                'valeurs_nutritionnelles' => json_encode([
                    ['nutrient' => 'Calories', 'value' => 0, 'unit' => 'kcal'],
                ]),
                'statut' => 'brouillon',
                'featured' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('produits')->insert($products);
    }
    
    private function copySampleImages(): void
    {
        $sampleImages = [
            'omega3.jpg',
            'protein.jpg',
            'vitamin-b.jpg',
            'magnesium.jpg',
            'creatine.jpg',
            'vitamin-d.jpg',
            'collagen.jpg',
            'minerals.jpg'
        ];
        
        $destinationDir = storage_path('app/public/products');
        
        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }
        
        foreach ($sampleImages as $image) {
            $destinationPath = $destinationDir . '/' . $image;$content = "This is a placeholder for {$image}. The GD library is required for actual image generation.";
            File::put($destinationPath, $content);
        }
    }
    
    private function getCategoryId(array $categoryIds, string $categoryName): int
    {
        $category = DB::table('categories')
            ->where('nom', $categoryName)
            ->first();
            
        return $category ? $category->id : $categoryIds[array_rand($categoryIds)];
    }
}