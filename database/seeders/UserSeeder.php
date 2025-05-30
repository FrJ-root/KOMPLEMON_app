<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder{
    public function run(){
        $admin = User::create([
            'name' => 'Admin',
            'role' => 'administrateur',
            'email' => 'admin@komplemon.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('administrateur');

        $productManager = User::create([
            'name' => 'Product Manager',
            'role' => 'gestionnaire_produits',
            'email' => 'products@komplemon.com',
            'password' => Hash::make('password'),
        ]);
        
        $productManager->assignRole('gestionnaire_produits');

        $orderManager = User::create([
            'name' => 'Order Manager',
            'email' => 'orders@komplemon.com',
            'role' => 'gestionnaire_commandes',
            'password' => Hash::make('password'),
        ]);
        
        $orderManager->assignRole('gestionnaire_commandes');

        $contentEditor = User::create([
            'name' => 'Content Editor',
            'role' => 'editeur_contenu',
            'email' => 'content@komplemon.com',
            'password' => Hash::make('password'),
        ]);
        
        $contentEditor->assignRole('editeur_contenu');
    }
}