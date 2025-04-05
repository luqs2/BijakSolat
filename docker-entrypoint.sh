#!/bin/bash
set -e

echo "🔄 Setting up application..."

# Create necessary directories
mkdir -p /var/www/storage/framework/{sessions,views,cache}
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Create storage link
if [ ! -L "/var/www/public/storage" ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

# Run migrations
echo "🔄 Running database migrations..."
php artisan migrate --force

# Clear and cache in production
if [ "$APP_ENV" = "production" ]; then
    echo "♨️ Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "♨️ Clearing caches for development..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
fi

echo "✅ Initialization complete!"

# Start PHP built-in server
exec "$@"
