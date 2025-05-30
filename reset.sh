#!/bin/bash

# Generate application key
php artisan key:generate

# Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Recreate autoloader
composer dump-autoload

echo "Reset complete! The application should now work correctly."
