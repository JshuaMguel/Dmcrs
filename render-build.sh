# Render Build Script
# This file tells Render how to build your Laravel app

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
npm install
npm run build

# Generate application key if not set
php artisan key:generate --force

# Clear all caches first to ensure fresh configuration
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache config for production with new values
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

echo "✅ Build completed successfully!"