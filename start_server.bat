@echo off
echo Clearing caches...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo Starting Laravel development server...
php artisan serve
