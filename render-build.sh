# Render Build Script
# This file tells Render how to build your Laravel app

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
npm install
npm run build

# Generate application key if not set
php artisan key:generate --force

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

echo "✅ Build completed successfully!"