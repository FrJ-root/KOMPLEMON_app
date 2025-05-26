<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ClientSeeder::class,
            OrderSeeder::class,
            ArticleBlogSeeder::class,
            TemoignageSeeder::class,
            PromotionSeeder::class,
        ]);
    }
}
