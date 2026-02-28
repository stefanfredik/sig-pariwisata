#!/bin/bash

set -e

echo "Waiting for MySQL to be ready..."
until php -r "new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "MySQL is unavailable - sleeping"
    sleep 2
done

echo "MySQL is up - continuing"

# Install composer dependencies
if [ ! -d "vendor" ]; then
    echo "Installing composer dependencies..."
    if [ "$APP_ENV" = "local" ] || [ "$APP_ENV" = "dev" ]; then
        composer install --no-interaction --optimize-autoloader
    else
        composer install --no-interaction --optimize-autoloader --no-dev
    fi
fi

# Install npm dependencies if node_modules doesn't exist (only if NPM is needed in app container)
if [ ! -d "node_modules" ] && command -v npm >/dev/null 2>&1; then
    echo "Installing npm dependencies..."
    npm install
fi

# Build frontend assets (only if not in local/dev mode where Vite is used)
if [ "$APP_ENV" != "local" ] && [ "$APP_ENV" != "dev" ]; then
    echo "Building frontend assets..."
    npm run build
fi

# Set proper permissions (only if root)
if [ "$(id -u)" = "0" ]; then
    echo "Setting permissions as root..."
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
else
    echo "Skipping root-level permission setup (running as $(id -u))..."
fi

# Run migrations (optional - comment out if you don't want auto-migration)
# echo "Running migrations..."
# php artisan migrate --force

echo "Starting PHP-FPM..."
exec "$@"
