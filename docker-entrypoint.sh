#!/bin/bash
set -e

echo "🔄 Running database migrations..."
php artisan migrate --force

echo "🔑 Generating application key if not set..."
php artisan key:generate --force

echo "🔗 Creating storage link..."
php artisan storage:link

echo "🗑️ Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "♨️ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "📝 Checking storage permissions..."
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "✅ Initialization complete!"

exec "$@"
