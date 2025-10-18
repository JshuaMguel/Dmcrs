#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Wait for database to be ready (optional but recommended)
echo "⏳ Waiting for database connection..."
max_attempts=30
attempt=0
until php artisan db:show > /dev/null 2>&1 || [ $attempt -eq $max_attempts ]; do
    attempt=$((attempt+1))
    echo "Attempt $attempt/$max_attempts: Database not ready yet..."
    sleep 2
done

if [ $attempt -eq $max_attempts ]; then
    echo "⚠️  Warning: Could not connect to database after $max_attempts attempts"
    echo "Continuing anyway - check your DB_* environment variables"
fi

# Run Laravel optimizations
echo "🔧 Running Laravel optimizations..."
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
