#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

echo "🔧 Running Laravel optimizations..."
echo "🔧 Running Laravel optimizations..."

# Create .env file if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "📝 Creating .env file..."
    cp /var/www/html/.env.example /var/www/html/.env 2>/dev/null || echo "APP_NAME=DMCRS" > /var/www/html/.env
fi

# Generate APP_KEY for production
echo "🔑 Generating APP_KEY..."
php artisan key:generate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Create storage link if needed
if [ ! -L /var/www/html/public/storage ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
fi

# Set final permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Laravel application is ready!"

# Execute the main container command (apache2-foreground)
exec "$@"
