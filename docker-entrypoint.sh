#!/bin/sh
set -e

echo "Starting container entrypoint..."

# Ensure required env vars
: ${DB_CONNECTION:=pgsql}
: ${DB_HOST:=127.0.0.1}
: ${DB_PORT:=5432}
: ${DB_USERNAME:=forge}
: ${DB_PASSWORD:=}
: ${DB_DATABASE:=forge}

wait_for_postgres() {
  echo "Waiting for Postgres at ${DB_HOST}:${DB_PORT}..."
  export PGPASSWORD="${DB_PASSWORD}"
  retries=0
  until psql -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}" -d "${DB_DATABASE}" -c '\q' >/dev/null 2>&1; do
    retries=$((retries+1))
    if [ "$retries" -ge 60 ]; then
      echo "Timed out waiting for Postgres after $retries attempts"
      return 1
    fi
    sleep 2
  done
  echo "Postgres is available"
}

wait_for_mysql() {
  echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
  retries=0
  until mysqladmin ping -h "${DB_HOST}" -P "${DB_PORT}" --silent; do
    retries=$((retries+1))
    if [ "$retries" -ge 60 ]; then
      echo "Timed out waiting for MySQL after $retries attempts"
      return 1
    fi
    sleep 2
  done
  echo "MySQL is available"
}

case "$DB_CONNECTION" in
  pgsql)
    if ! wait_for_postgres; then
      echo "Database did not become available. Exiting."
      exit 1
    fi
    ;;
  mysql)
    if ! wait_for_mysql; then
      echo "Database did not become available. Exiting."
      exit 1
    fi
    ;;
  sqlite)
    # Ensure the sqlite database file exists and is writable
    DB_PATH=${DB_DATABASE:-/var/www/html/database/database.sqlite}
    echo "Using SQLite database at ${DB_PATH}"
    mkdir -p "$(dirname "$DB_PATH")"
    if [ ! -f "$DB_PATH" ]; then
      touch "$DB_PATH"
      chown www-data:www-data "$DB_PATH" || true
      chmod 664 "$DB_PATH" || true
      echo "Created SQLite file"
    fi
    ;;
  *)
    echo "Unknown DB_CONNECTION=$DB_CONNECTION; continuing without waiting."
    ;;
esac

echo "Running Laravel maintenance tasks..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan migrate --force || true
php artisan storage:link || true

echo "Starting supervisor..."
exec supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
