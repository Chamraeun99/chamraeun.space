#!/bin/sh

cd /var/www/html

# Use Render's PORT or default to 8000
PORT=${PORT:-8000}

# Export SSL mode for psql.
# Use "prefer" by default so local/docker Postgres works without SSL,
# while still allowing SSL when DB_SSLMODE=require is explicitly set (e.g. Neon/managed DB).
export PGSSLMODE="${DB_SSLMODE:-prefer}"

# Install composer dependencies if vendor is missing
if [ ! -f vendor/autoload.php ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# Generate app key only if not already set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction 2>/dev/null || true
fi

# Skip cache clear commands on startup to avoid local Docker hangs.

# ============================================================
# DATABASE: Run pending migrations (preserves existing data)
# ============================================================
echo "==> [DB] Connection info:"
echo "    DB_HOST=${DB_HOST}"
echo "    DB_PORT=${DB_PORT}"
echo "    DB_DATABASE=${DB_DATABASE}"
echo "    DB_USERNAME=${DB_USERNAME}"
echo "    DB_SSLMODE=${DB_SSLMODE}"
echo "    PGSSLMODE=${PGSSLMODE}"

MIGRATE_EXIT=0
if [ "${RUN_MIGRATIONS}" = "1" ] || [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "==> [DB] Running pending migrations..."
    php artisan migrate --force --no-interaction 2>&1
    MIGRATE_EXIT=$?

    if [ $MIGRATE_EXIT -ne 0 ]; then
        echo ""
        echo "==> [DB] !!! Migration failed (exit code: $MIGRATE_EXIT) !!!"
    fi
else
    echo "==> [DB] Skipping migrations (set RUN_MIGRATIONS=1 to enable on startup)."
    if [ "${APP_ENV}" = "production" ]; then
        echo "==> [DB] Hint: In Render → your web service → Environment, add RUN_MIGRATIONS=1 (or run once: php artisan migrate --force)."
    fi
fi

# Optional local seed (enabled in docker-compose via RUN_DB_SEED=1).
if [ -n "$RUN_DB_SEED" ] && [ "$RUN_DB_SEED" != "0" ] && [ "$RUN_DB_SEED" != "false" ] && [ $MIGRATE_EXIT -eq 0 ]; then
    echo "==> [DB] Seeding demo data..."
    php artisan db:seed --force --no-interaction 2>&1 || true
fi

# Skip config/route caching on startup in local Docker.

# Skip storage:link on startup to avoid local container hangs.

# Skip chown on startup (can hang on Windows bind mounts).

echo "==> Starting Laravel on port ${PORT}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT}
