<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run(): void{
        $roles = [
            [
                'name' => 'administrateur',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'description' => 'Administrator role with full permissions',
            ],
            [
                'name' => 'user',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'description' => 'Regular user with limited permissions',
            ],
        ];

        DB::table('roles')->insertOrIgnore($roles);
    }
}
