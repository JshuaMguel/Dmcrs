#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Wait for database to be ready with improved connection check
echo "⏳ Waiting for database connection..."
max_attempts=15
attempt=0
db_connected=false

while [ $attempt -lt $max_attempts ]; do
    attempt=$((attempt+1))
    
    if php artisan db:show > /dev/null 2>&1; then
        echo "✅ Database connection successful!"
        db_connected=true
        break
    fi
    
    if [ $attempt -lt $max_attempts ]; then
        echo "Attempt $attempt/$max_attempts: Waiting for database... (retrying in 3s)"
        sleep 3
    fi
done

if [ "$db_connected" = false ]; then
    echo "❌ ERROR: Could not connect to database after $max_attempts attempts"
    echo "Please check your Railway MySQL credentials:"
    echo "  - DB_HOST: $DB_HOST"
    echo "  - DB_PORT: $DB_PORT"
    echo "  - DB_DATABASE: $DB_DATABASE"
    echo "  - DB_USERNAME: $DB_USERNAME"
    echo ""
    echo "Exiting... Please fix database configuration and redeploy."
    exit 1
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
