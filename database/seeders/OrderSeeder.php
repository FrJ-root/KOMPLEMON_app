<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $clientIds = DB::table('clients')->pluck('id')->toArray();
        $productIds = DB::table('produits')->pluck('id')->toArray();
        $statuses = ['en attente', 'confirmé', 'expédié', 'livré', 'annulé'];
        
        $orders = [];
        $orderDetails = [];
        
        for ($i = 0; $i < 20; $i++) {
            $clientId = $clientIds[array_rand($clientIds)];
            $status = $statuses[array_rand($statuses)];
            $orderDate = $faker->dateTimeBetween('-6 months', 'now');
            
            $orders[] = [
                'date_commande' => $orderDate,
                'client_id' => $clientId,
                'statut' => $status,
                'total' => 0,
                'historique' => json_encode([
                    [
                        'date' => $orderDate->format('Y-m-d H:i:s'),
                        'commentaire' => 'Commande créée',
                        'statut' => 'en attente',
                    ]
                ]),
                'created_at' => $orderDate,
                'updated_at' => now(),
            ];
        }
        
        DB::table('commandes')->insert($orders);
        
        $orderIds = DB::table('commandes')->pluck('id')->toArray();
        
        foreach ($orderIds as $orderId) {
            $itemCount = rand(1, 5);
            $total = 0;
            
            for ($j = 0; $j < $itemCount; $j++) {
                $productId = $productIds[array_rand($productIds)];
                $product = DB::table('produits')->where('id', $productId)->first();
                $quantity = rand(1, 3);
                $price = $product->prix_promo ?? $product->prix;
                $lineTotal = $price * $quantity;
                $total += $lineTotal;
                
                $orderDetails[] = [
                    'commande_id' => $orderId,
                    'produit_id' => $productId,
                    'quantite' => $quantity,
                    'prix_unitaire' => $price,
                ];
            }
            
            DB::table('commandes')
                ->where('id', $orderId)
                ->update(['total' => $total]);
        }
        
        DB::table('details_commandes')->insert($orderDetails);
    }
}