<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder{
    public function run(){
        $admin = User::create([
            'name' => 'Admin',
            'role' => 'administrateur',
            'email' => 'admin@komplemon.com',
            'password' => Hash::make('password'),
        ]);

        $this->assignRoleDirectly($admin->id, 'administrateur');

        $productManager = User::create([
            'name' => 'Product Manager',
            'role' => 'gestionnaire_produits',
            'email' => 'products@komplemon.com',
            'password' => Hash::make('password'),
        ]);
        
        $this->assignRoleDirectly($productManager->id, 'gestionnaire_produits');

        $orderManager = User::create([
            'name' => 'Order Manager',
            'email' => 'orders@komplemon.com',
            'role' => 'gestionnaire_commandes',
            'password' => Hash::make('password'),
        ]);
        
        $this->assignRoleDirectly($orderManager->id, 'gestionnaire_commandes');

        $contentEditor = User::create([
            'name' => 'Content Editor',
            'role' => 'editeur_contenu',
            'email' => 'content@komplemon.com',
            'password' => Hash::make('password'),
        ]);
        
        $this->assignRoleDirectly($contentEditor->id, 'editeur_contenu');
    }

    private function assignRoleDirectly($userId, $roleName){
        if (Schema::hasTable('model_has_roles')) {
            $roleId = DB::table('roles')->where('name', $roleName)->value('id');
            
            if ($roleId) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_id' => $userId,
                    'model_type' => User::class,
                ]);
            }
        }
    }
}