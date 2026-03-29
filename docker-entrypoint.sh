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

elif [[ "$RENDER" == "true" ]] || [[ -n "$RENDER_SERVICE_ID" ]] || [[ -n "$DATABASE_URL" ]]; then
    echo "🌐 Detected Render environment - Using PostgreSQL"
    echo "🔍 Checking for DATABASE_URL environment variable..."
    
    # Parse DATABASE_URL if provided (Render automatically provides this)
    if [[ -n "$DATABASE_URL" ]]; then
        echo "✅ DATABASE_URL found in environment"
        echo "📋 Using DATABASE_URL from Render (PHP parse_url — supports Supabase pooler user & special chars)..."
        # Bash cut/split breaks on passwords/usernames with ":" or "@"; Supabase also expects SSL.
        eval "$(php <<'PARSEDBURL'
<?php
$url = getenv('DATABASE_URL');
if (!$url) {
    fwrite(STDERR, "DATABASE_URL empty\n");
    exit(1);
}
$p = parse_url($url);
if (!$p || empty($p['host'])) {
    fwrite(STDERR, "Invalid DATABASE_URL\n");
    exit(1);
}
$user = isset($p['user']) ? rawurldecode($p['user']) : '';
$pass = isset($p['pass']) ? rawurldecode($p['pass']) : '';
$host = $p['host'];
$port = isset($p['port']) ? (string) (int) $p['port'] : '5432';
$db = isset($p['path']) ? ltrim($p['path'], '/') : 'postgres';
$db = explode('?', $db, 2)[0];
if ($db === '') {
    $db = 'postgres';
}
if (strpos($host, 'dpg-') === 0 && strpos($host, '.singapore-postgres.render.com') === false) {
    $host .= '.singapore-postgres.render.com';
}
foreach (['DB_USERNAME' => $user, 'DB_PASSWORD' => $pass, 'DB_HOST' => $host, 'DB_PORT' => $port, 'DB_DATABASE' => $db] as $name => $value) {
    echo 'export ' . $name . '=' . escapeshellarg($value) . "\n";
}
PARSEDBURL
)"
        echo "✅ Parsed DATABASE_URL successfully"
        echo "   Host: ${DB_HOST}"
        echo "   Port: ${DB_PORT}"
        echo "   Database: ${DB_DATABASE}"
        echo "   DB user: ${DB_USERNAME}"
    else
        # Use individual environment variables (set in Render dashboard)
        echo "⚠️  DATABASE_URL not found, checking individual DB environment variables..."
        echo "   DB_HOST=${DB_HOST:-NOT SET}"
        echo "   DB_DATABASE=${DB_DATABASE:-NOT SET}"
        echo "   DB_USERNAME=${DB_USERNAME:-NOT SET}"
        echo "   DB_PASSWORD=${DB_PASSWORD:+SET (hidden)}${DB_PASSWORD:-NOT SET}"
        
        DB_HOST=${DB_HOST}
        DB_PORT=${DB_PORT:-5432}
        DB_DATABASE=${DB_DATABASE}
        DB_USERNAME=${DB_USERNAME}
        DB_PASSWORD=${DB_PASSWORD}
        
        # Ensure hostname has full domain if it's a Render PostgreSQL hostname
        if [[ "$DB_HOST" == dpg-* ]] && [[ "$DB_HOST" != *.singapore-postgres.render.com ]]; then
            DB_HOST="${DB_HOST}.singapore-postgres.render.com"
            echo "✅ Added full domain to hostname: ${DB_HOST}"
        fi
        
        # Check if required variables are set
        if [[ -z "$DB_HOST" ]] || [[ -z "$DB_DATABASE" ]] || [[ -z "$DB_USERNAME" ]] || [[ -z "$DB_PASSWORD" ]]; then
            echo ""
            echo "❌ ERROR: Database environment variables not set!"
            echo ""
            echo "📋 SOLUTION: Set environment variables in Render dashboard:"
            echo ""
            echo "   OPTION 1 (EASIEST):"
            echo "   1. Go to your Web Service → Environment tab"
            echo "   2. Click 'Link Database' button"
            echo "   3. Select your PostgreSQL database"
            echo ""
            echo "   OPTION 2 (MANUAL):"
            echo "   1. Go to Web Service → Environment tab"
            echo "   2. Add DATABASE_URL from your provider (Render Postgres or Supabase URI)."
            echo ""
            echo "   OR set individual variables: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD"
            echo ""
            echo "   ⚠️  IMPORTANT: Use External Database URL with full hostname (.singapore-postgres.render.com)"
            echo ""
            exit 1
        fi
    fi
    
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
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
DB_SSLMODE=require

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
    echo "📊 Database: ${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

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
elif [[ "$RENDER" == "true" ]] || [[ -n "$RENDER_SERVICE_ID" ]] || [[ -n "$DATABASE_URL" ]]; then
    # Test Render PostgreSQL connection - reuse variables already parsed above
    # If DATABASE_URL was parsed above, reuse those variables
    if [[ -n "$DATABASE_URL" ]] && [[ -n "$DB_HOST" ]]; then
        # Use already parsed variables from above
        TEST_DB_HOST=${DB_HOST}
        TEST_DB_PORT=${DB_PORT}
        TEST_DB_DATABASE=${DB_DATABASE}
        TEST_DB_USERNAME=${DB_USERNAME}
        TEST_DB_PASSWORD=${DB_PASSWORD}
    else
        # Parse again for testing (if not parsed above)
        if [[ -n "$DATABASE_URL" ]]; then
            eval "$(php <<'PARSEDBURL'
<?php
$url = getenv('DATABASE_URL');
if (!$url) { exit(1); }
$p = parse_url($url);
if (!$p || empty($p['host'])) { exit(1); }
$user = isset($p['user']) ? rawurldecode($p['user']) : '';
$pass = isset($p['pass']) ? rawurldecode($p['pass']) : '';
$host = $p['host'];
$port = isset($p['port']) ? (string) (int) $p['port'] : '5432';
$db = isset($p['path']) ? ltrim($p['path'], '/') : 'postgres';
$db = explode('?', $db, 2)[0];
if ($db === '') { $db = 'postgres'; }
if (strpos($host, 'dpg-') === 0 && strpos($host, '.singapore-postgres.render.com') === false) {
    $host .= '.singapore-postgres.render.com';
}
foreach (['TEST_DB_USERNAME' => $user, 'TEST_DB_PASSWORD' => $pass, 'TEST_DB_HOST' => $host, 'TEST_DB_PORT' => $port, 'TEST_DB_DATABASE' => $db] as $name => $value) {
    echo 'export ' . $name . '=' . escapeshellarg($value) . "\n";
}
PARSEDBURL
)"
        else
            TEST_DB_HOST=${DB_HOST}
            TEST_DB_PORT=${DB_PORT:-5432}
            TEST_DB_DATABASE=${DB_DATABASE}
            TEST_DB_USERNAME=${DB_USERNAME}
            TEST_DB_PASSWORD=${DB_PASSWORD}
            
            # Ensure hostname has full domain if it's a Render PostgreSQL hostname
            if [[ "$TEST_DB_HOST" == dpg-* ]] && [[ "$TEST_DB_HOST" != *.singapore-postgres.render.com ]]; then
                TEST_DB_HOST="${TEST_DB_HOST}.singapore-postgres.render.com"
            fi
        fi
    fi
    
    echo "🔍 Testing connection to: ${TEST_DB_HOST}:${TEST_DB_PORT}/${TEST_DB_DATABASE} (user: ${TEST_DB_USERNAME})"
    export TEST_DB_HOST TEST_DB_PORT TEST_DB_DATABASE TEST_DB_USERNAME TEST_DB_PASSWORD
    
    # Test connection (sslmode=require — Supabase / most cloud Postgres)
    if php <<'PHPCONNTEST'
<?php
try {
    $h = getenv('TEST_DB_HOST');
    $port = getenv('TEST_DB_PORT');
    $db = getenv('TEST_DB_DATABASE');
    $u = getenv('TEST_DB_USERNAME');
    $w = getenv('TEST_DB_PASSWORD');
    $dsn = "pgsql:host={$h};port={$port};dbname={$db};sslmode=require";
    $pdo = new PDO($dsn, $u, $w, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo 'Render PostgreSQL connected successfully';
    exit(0);
} catch (Throwable $e) {
    echo 'Render PostgreSQL connection failed: ' . $e->getMessage();
    exit(1);
}
PHPCONNTEST
then
        echo "✅ Render PostgreSQL connection successful!"
    else
        echo "❌ Render PostgreSQL connection failed!"
        echo ""
        echo "🔍 Troubleshooting:"
        echo "   1. Verify DATABASE_URL is set in Render dashboard → Environment tab"
        echo "   2. For Render Postgres: use External Database URL with full hostname (*.singapore-postgres.render.com)."
        echo "   3. For Supabase: use the Session pooler URI from the dashboard; reset DB password if unsure."
        echo "   4. Current connection attempt: ${TEST_DB_HOST}:${TEST_DB_PORT}/${TEST_DB_DATABASE}"
        echo "   5. Verify database is running and accessible"
        echo "   6. After setting environment variables, REDEPLOY the service"
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
php artisan migrate --force
echo "✅ Database migrations attempted!"

# Always run admin user seeder (even if some migrations failed)
echo "👤 Creating admin user..."
if php artisan db:seed --class=AdminUserSeeder --force; then
    echo "✅ Admin user created successfully!"
    echo "📧 Login: admin@ustp.edu.ph / admin2025"
else
    echo "ℹ️ Admin user may already exist"
fi

# Run core data seeders (subjects, rooms, departments)
echo "📦 Seeding core application data..."
if php artisan db:seed --force; then
    echo "✅ Core data seeded successfully!"
    echo "📚 Subjects, Rooms, and Departments ready!"
else
    echo "ℹ️ Core data may already exist"
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
