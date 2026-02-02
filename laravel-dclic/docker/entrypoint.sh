#!/bin/bash
set -e

echo "🚀 Laravel starting..."

# --- Ensure .env exists (required by Laravel)
[ -f /app/.env ] || touch /app/.env

# --- Wait for DB
echo "⏳ Waiting for database..."
for i in {1..30}; do
  nc -z "${DB_HOST}" "${DB_PORT:-3306}" && break
  sleep 2
done

# --- Laravel
php artisan migrate --force || true
php artisan config:clear
php artisan config:cache

# --- Permissions
chown -R www-data:www-data /app
chmod -R 775 storage bootstrap/cache

# --- Nginx
nginx -t || exit 1

exec /usr/bin/supervisord
