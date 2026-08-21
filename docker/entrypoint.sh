#!/bin/bash
set -e

git config --global --add safe.directory /var/www/html 2>/dev/null || true

# Ensure directories and permissions for storage and bootstrap/cache
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/bootstrap/cache

chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# If vendor directory does not exist, install composer dependencies
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

exec "$@"
