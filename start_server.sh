#!/bin/bash

# Clear caches first
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Start the Laravel development server
echo "Starting Laravel development server..."
php artisan serve
