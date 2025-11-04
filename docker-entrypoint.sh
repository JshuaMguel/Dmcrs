#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Detect platform and configure accordingly
if [[ "$RAILWAY_ENVIRONMENT" == "production" ]] || [[ -n "$RAILWAY_PROJECT_ID" ]] || [[ -n "$MYSQL_URL" ]]; then
    echo "� Detected Railway environment - Using MySQL"
    
    # Railway MySQL Configuration
    cat > /var/www/html/.env << EOF
APP_NAME=DMCRS
APP_ENV=production
APP_KEY=base64:2rQGTvLTOAsNIU60L2ruI+QRsQ4XjNnL5bbxtNQOg88=
APP_DEBUG=false
APP_URL=https://ustp-balubal-dmcrs.up.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=shinkansen.proxy.rlwy.net
DB_PORT=43545
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=xmiafwpddAhnULrLRWqIYLGTigqZomVq

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ustpbalubal.dmcrs@gmail.com
MAIL_PASSWORD=gmjauouwuleegday
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ustpbalubal.dmcrs@gmail.com
MAIL_FROM_NAME="USTP Balubal Campus - DMCRS"

BROADCAST_DRIVER=log
APP_TIMEZONE=Asia/Manila
EOF

    echo "✅ Railway MySQL configuration applied"

elif [[ "$RENDER" == "true" ]] || [[ -n "$RENDER_SERVICE_ID" ]]; then
    echo "🌐 Detected Render environment - Using PostgreSQL"
    
    # Render PostgreSQL Configuration  
    cat > /var/www/html/.env << EOF
APP_NAME=DMCRS
APP_ENV=production
APP_KEY=base64:2rQGTvLTOAsNIU60L2ruI+QRsQ4XjNnL5bbxtNQOg88=
APP_DEBUG=false
APP_URL=https://dmcrs.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=pgsql
DB_HOST=dpg-d44krgripnbc73al8mi0-a
DB_PORT=5432
DB_DATABASE=dmcrs_db
DB_USERNAME=dmcrs_db_user
DB_PASSWORD=fYj2OPwnQ9H8cbt50EBfj1sYj8xEMVYB

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ustpbalubal.dmcrs@gmail.com
MAIL_PASSWORD=gmjauouwuleegday
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ustpbalubal.dmcrs@gmail.com
MAIL_FROM_NAME="USTP Balubal Campus - DMCRS"

BROADCAST_DRIVER=log
APP_TIMEZONE=Asia/Manila
EOF

    echo "✅ Render PostgreSQL configuration applied"

else
    echo "🤔 Unknown environment - Using default MySQL"
    
    # Default MySQL Configuration
    cat > /var/www/html/.env << EOF
APP_NAME=DMCRS
APP_ENV=production
APP_KEY=base64:2rQGTvLTOAsNIU60L2ruI+QRsQ4XjNnL5bbxtNQOg88=
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dmcrs
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ustpbalubal.dmcrs@gmail.com
MAIL_PASSWORD=gmjauouwuleegday
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ustpbalubal.dmcrs@gmail.com
MAIL_FROM_NAME="USTP Balubal Campus - DMCRS"

BROADCAST_DRIVER=log
APP_TIMEZONE=Asia/Manila
EOF

    echo "✅ Default MySQL configuration applied"
fi

# Test database connection based on detected platform
echo "⏳ Testing database connection..."
if [[ "$RAILWAY_ENVIRONMENT" == "production" ]] || [[ -n "$RAILWAY_PROJECT_ID" ]] || [[ -n "$MYSQL_URL" ]]; then
    # Test Railway MySQL connection
    if php -r "
    try {
        \$pdo = new PDO('mysql:host=shinkansen.proxy.rlwy.net;port=43545;dbname=railway', 'root', 'xmiafwpddAhnULrLRWqIYLGTigqZomVq');
        echo 'Railway MySQL connected successfully';
        exit(0);
    } catch (Exception \$e) {
        echo 'Railway MySQL connection failed: ' . \$e->getMessage();
        exit(1);
    }
    "; then
        echo "✅ Railway MySQL connection successful!"
    else
        echo "❌ Railway MySQL connection failed!"
        exit 1
    fi
elif [[ "$RENDER" == "true" ]] || [[ -n "$RENDER_SERVICE_ID" ]]; then
    # Test Render PostgreSQL connection
    if php -r "
    try {
        \$pdo = new PDO('pgsql:host=dpg-d44krgripnbc73al8mi0-a;port=5432;dbname=dmcrs_db', 'dmcrs_db_user', 'fYj2OPwnQ9H8cbt50EBfj1sYj8xEMVYB');
        echo 'Render PostgreSQL connected successfully';
        exit(0);
    } catch (Exception \$e) {
        echo 'Render PostgreSQL connection failed: ' . \$e->getMessage();
        exit(1);
    }
    "; then
        echo "✅ Render PostgreSQL connection successful!"
    else
        echo "❌ Render PostgreSQL connection failed!"
        exit 1
    fi
else
    echo "ℹ️ Skipping connection test for default environment"
fi

# Laravel application setup
echo "🔧 Setting up Laravel application..."

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache configuration for production
echo "📦 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗃️ Running database migrations..."
if php artisan migrate --force; then
    echo "✅ Database migrations completed successfully!"
    
    # Run admin user seeder for fresh databases
    echo "👤 Creating admin user..."
    if php artisan db:seed --class=AdminUserSeeder --force; then
        echo "✅ Admin user created successfully!"
        echo "📧 Login: admin@ustp.edu.ph / admin123"
    else
        echo "ℹ️ Admin user may already exist"
    fi
else
    echo "⚠️ Database migrations failed, but continuing..."
fi

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

echo "🎉 Laravel application setup completed!"
echo "🚀 Starting web server..."

# Start Apache
exec apache2-foreground
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
