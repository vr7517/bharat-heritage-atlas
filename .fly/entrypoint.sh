#!/usr/bin/env bash
set -e

echo "[Fly.io Entrypoint] Starting Bharat Heritage Atlas container..."

# 1. Setup Persistent Storage Paths
STORAGE_DIR="/var/www/html/storage"
DB_DIR="${STORAGE_DIR}/database"
DB_FILE="${DB_DIR}/database.sqlite"

mkdir -p "${STORAGE_DIR}/app/public"
mkdir -p "${STORAGE_DIR}/framework/cache/data"
mkdir -p "${STORAGE_DIR}/framework/sessions"
mkdir -p "${STORAGE_DIR}/framework/views"
mkdir -p "${STORAGE_DIR}/logs"
mkdir -p "${DB_DIR}"

# 2. Initialize SQLite Database if missing on the persistent volume
if [ ! -f "${DB_FILE}" ]; then
    echo "[Fly.io Entrypoint] Initializing SQLite database at ${DB_FILE}..."
    if [ -f "/var/www/html/database/database.sqlite" ]; then
        echo "[Fly.io Entrypoint] Copying packaged SQLite database to persistent volume..."
        cp /var/www/html/database/database.sqlite "${DB_FILE}"
    else
        touch "${DB_FILE}"
        echo "[Fly.io Entrypoint] Running fresh database migrations and seeders..."
        php /var/www/html/artisan migrate --force --seed
    fi
else
    echo "[Fly.io Entrypoint] Running pending migrations on persistent database..."
    php /var/www/html/artisan migrate --force
fi

# 3. Create Public Storage Symlink
php /var/www/html/artisan storage:link || true

# 4. Cache Configurations, Routes, and Views for Production
echo "[Fly.io Entrypoint] Optimizing Laravel caches..."
php /var/www/html/artisan config:cache || true
php /var/www/html/artisan route:cache || true
php /var/www/html/artisan view:cache || true

# 5. Fix permissions for www-data
chown -R www-data:www-data "${STORAGE_DIR}" /var/www/html/bootstrap/cache
chmod -R 775 "${STORAGE_DIR}" /var/www/html/bootstrap/cache

echo "[Fly.io Entrypoint] Initialization complete. Starting processes..."
exec "$@"
