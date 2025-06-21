<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ArticleBlogSeeder::class,
            TemoignageSeeder::class,
            PromotionSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ClientSeeder::class,
            OrderSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
