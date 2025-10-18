#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# If MYSQL_URL is set (Railway), parse it and set Laravel env vars
if [ ! -z "$MYSQL_URL" ]; then
    echo "🔧 Detected Railway MYSQL_URL, configuring database connection..."
    # Parse MYSQL_URL format: mysql://user:password@host:port/database
    DB_CONNECTION="mysql"
    DB_USERNAME=$(echo $MYSQL_URL | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    DB_PASSWORD=$(echo $MYSQL_URL | sed -n 's/.*:\/\/[^:]*:\([^@]*\)@.*/\1/p')
    DB_HOST=$(echo $MYSQL_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
    DB_PORT=$(echo $MYSQL_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    DB_DATABASE=$(echo $MYSQL_URL | sed -n 's/.*\/\(.*\)/\1/p')
    
    export DB_CONNECTION DB_USERNAME DB_PASSWORD DB_HOST DB_PORT DB_DATABASE
    
    echo "✅ Database configured from MYSQL_URL"
    echo "  - Host: $DB_HOST:$DB_PORT"
    echo "  - Database: $DB_DATABASE"
fi

# Wait for database to be ready with improved connection check
echo "⏳ Waiting for database connection..."
max_attempts=30
attempt=0
db_connected=false

while [ $attempt -lt $max_attempts ]; do
    attempt=$((attempt+1))

    # Try to connect using PHP PDO
    if php -r "new PDO('mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD');" > /dev/null 2>&1; then
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
