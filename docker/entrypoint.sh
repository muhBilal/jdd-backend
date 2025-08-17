#!/bin/sh

# Exit on any error
set -e

echo "Starting Laravel application setup..."

# Clear any existing cached config that might interfere
echo "Clearing any existing cached configuration..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Check if APP_KEY is set, if not generate one
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY not set, generating a new one..."
    php artisan key:generate --force --no-interaction
    echo "APP_KEY generated: $(php artisan tinker --execute='echo config("app.key");' 2>/dev/null | tail -1)"
else
    echo "APP_KEY is already set"
fi

# Wait a moment to ensure environment is ready
sleep 2

# Cache configuration for better performance (now with proper env vars)
echo "Caching configuration with environment variables..."
php artisan config:cache || echo "Config cache failed, continuing..."

echo "Laravel application setup completed!"

# Execute the main command
exec "$@"
