#!/bin/bash

composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
php artisan optimize:clear
echo "Spatie Permission package set up successfully!"