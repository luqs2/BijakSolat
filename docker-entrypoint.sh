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

if [ "$APP_ENV" = "production" ]; then
    echo "🚦 Caching routes for production..."
    php artisan route:cache
else
    echo "🚦 Skipping route cache for development..."
fi

php artisan view:cache

echo "📝 Checking storage permissions..."
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "✅ Initialization complete!"

exec "$@"
