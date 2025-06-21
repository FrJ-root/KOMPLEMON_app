<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run(): void{
        if (!Schema::hasTable('permissions')) {
            $this->createPermissionsTable();
        }
        
        if (!Schema::hasTable('roles')) {
            $this->createRolesTable();
        }
        
        if (!Schema::hasTable('role_has_permissions')) {
            $this->createRolePermissionsTable();
        }
        
        $permissions = [
            ['name' => 'manage_products', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_orders', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_content', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manage_users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore($permission);
        }
        
        $roles = [
            ['name' => 'administrateur', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'gestionnaire_produits', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'gestionnaire_commandes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'editeur_contenu', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role);
        }
        
        $rolePermissions = [
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 4],
            ['role_id' => 1, 'permission_id' => 5],
            
            ['role_id' => 2, 'permission_id' => 1],
            
            ['role_id' => 3, 'permission_id' => 2],
            ['role_id' => 3, 'permission_id' => 3],
            
            ['role_id' => 4, 'permission_id' => 4],
        ];
        
        foreach ($rolePermissions as $rp) {
            DB::table('role_has_permissions')->insertOrIgnore($rp);
        }
    }
    
    private function createPermissionsTable()
    {
        Schema::create('permissions', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->timestamps();
            
            $table->unique(['name', 'guard_name']);
        });
    }
    
    private function createRolesTable()
    {
        Schema::create('roles', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->timestamps();
            
            $table->unique(['name', 'guard_name']);
        });
    }
    
    private function createRolePermissionsTable()
    {
        Schema::create('role_has_permissions', function ($table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            
            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });
    }
}
