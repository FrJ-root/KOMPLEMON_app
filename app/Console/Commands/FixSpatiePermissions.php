<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixSpatiePermissions extends Command
{
    protected $signature = 'permissions:setup';
    
    protected $description = 'Set up and configure Spatie Laravel Permission package';

    public function handle()
    {
        $this->info('Setting up Spatie Laravel Permission package...');
        
        // Publish the config file
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--tag' => 'permission-config'
        ]);
        
        // Publish the migrations
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--tag' => 'permission-migrations'
        ]);
        
        // Run the migrations
        $this->call('migrate');
        
        $this->info('Spatie Laravel Permission setup completed successfully!');
    }
}
