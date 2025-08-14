#!/bin/bash
echo "Running migration to rename syllabi table to syllabus..."
php artisan migrate

echo "Clearing caches..."
php artisan cache:clear
php artisan route:clear
php artisan config:clear

echo "Database table renamed and caches cleared successfully!"