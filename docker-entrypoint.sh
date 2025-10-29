#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Create .env file with Railway configuration
echo "📝 Creating .env file..."
cat > /var/www/html/.env << 'EOF'
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
