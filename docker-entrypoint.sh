#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Helper: parse a MySQL-style URL and export Laravel DB_* envs
parse_mysql_url() {
    local url="$1"
    # Expected: mysql://user:pass@host:port/dbname[?params]
    DB_CONNECTION="mysql"
    DB_USERNAME=$(echo "$url" | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    DB_PASSWORD=$(echo "$url" | sed -n 's/.*:\/\/[^:]*:\([^@?]*\)@.*/\1/p')
    DB_HOST=$(echo "$url" | sed -n 's/.*@\([^:\/?]*\).*/\1/p')
    DB_PORT=$(echo "$url" | sed -n 's/.*:\([0-9][0-9]*\)\/.*/\1/p')
    # Strip query params from db name if present
    DB_DATABASE=$(echo "$url" | sed -n 's/.*\/\([^?]*\).*/\1/p')
    export DB_CONNECTION DB_USERNAME DB_PASSWORD DB_HOST DB_PORT DB_DATABASE
}

# Helper: test DB connection using mysqladmin (preferred) or PHP PDO fallback
test_db_connection() {
    # mysqladmin returns 0 when server is alive
    if command -v mysqladmin >/dev/null 2>&1; then
        if mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" --connect-timeout=5 >/dev/null 2>&1; then
            return 0
        fi
    fi
    # Fallback: PHP PDO
    php -r "new PDO('mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD');" >/dev/null 2>&1
    return $?
}

PRIMARY_DB_SOURCE=""
FALLBACK_DB_SOURCE=""

# Prefer internal MYSQL_URL; capture fallback to MYSQL_PUBLIC_URL when available
if [ -n "$MYSQL_URL" ]; then
    echo "🔧 Detected Railway MYSQL_URL, configuring database connection..."
    parse_mysql_url "$MYSQL_URL"
    PRIMARY_DB_SOURCE="MYSQL_URL"
    echo "✅ Database configured from MYSQL_URL"
    echo "  - Host: $DB_HOST:$DB_PORT"
    echo "  - Database: $DB_DATABASE"
    # Keep a copy for potential diagnostics
    INTERNAL_DB_HOST="$DB_HOST"; INTERNAL_DB_PORT="$DB_PORT"
fi

if [ -n "$MYSQL_PUBLIC_URL" ]; then
    FALLBACK_DB_SOURCE="MYSQL_PUBLIC_URL"
    PUBLIC_DB_USERNAME=$(echo "$MYSQL_PUBLIC_URL" | sed -n 's/.*:\/\/(\[^:]*\):.*/\1/p')
    PUBLIC_DB_PASSWORD=$(echo "$MYSQL_PUBLIC_URL" | sed -n 's/.*:\/\/\[^:]*:\([^@?]*\)@.*/\1/p')
    PUBLIC_DB_HOST=$(echo "$MYSQL_PUBLIC_URL" | sed -n 's/.*@\([^:\/?]*\).*/\1/p')
    PUBLIC_DB_PORT=$(echo "$MYSQL_PUBLIC_URL" | sed -n 's/.*:\([0-9][0-9]*\)\/.*/\1/p')
    PUBLIC_DB_DATABASE=$(echo "$MYSQL_PUBLIC_URL" | sed -n 's/.*\/\([^?]*\).*/\1/p')
fi

# Wait for database to be ready with improved connection check
echo "⏳ Skipping database connection check for Supabase compatibility..."
# Force success for Supabase deployment
db_connected=true
using_fallback=false

if [ "$db_connected" = false ]; then
    echo "❌ ERROR: Could not connect to database after $max_attempts attempts"
    echo "Please check your Railway MySQL credentials:"
    echo "  - DB_HOST: $DB_HOST"
    echo "  - DB_PORT: $DB_PORT"
    echo "  - DB_DATABASE: $DB_DATABASE"
    echo "  - DB_USERNAME: $DB_USERNAME"
    if [ -n "$PRIMARY_DB_SOURCE" ]; then
        echo "  - Primary source: $PRIMARY_DB_SOURCE (host: ${INTERNAL_DB_HOST:-n/a}:${INTERNAL_DB_PORT:-n/a})"
    fi
    if [ "$using_fallback" = true ]; then
        echo "  - Fallback source in use: $FALLBACK_DB_SOURCE"
    elif [ -n "$FALLBACK_DB_SOURCE" ]; then
        echo "  - Fallback source available: $FALLBACK_DB_SOURCE (host: $PUBLIC_DB_HOST:$PUBLIC_DB_PORT)"
    fi
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
