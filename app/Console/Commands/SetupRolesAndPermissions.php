<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupRolesAndPermissions extends Command
{
    protected $signature = 'setup:roles-permissions';
    protected $description = 'Setup roles and permissions tables and seed them';

    public function handle()
    {
        $this->info('Setting up roles and permissions...');
        
        // Run migrations
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--path' => 'database/migrations/2023_08_10_000000_create_permission_tables.php']);
        $this->info(Artisan::output());
        
        // Run seeds
        $this->info('Running seeds...');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
        $this->info(Artisan::output());
        
        $this->info('Roles and permissions setup completed successfully!');
        
        return Command::SUCCESS;
    }
}
