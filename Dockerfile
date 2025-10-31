# ────────────────────────────────────────────────
# Dockerfile for Laravel + PostgreSQL + PHP 8.2 + Nginx
# ────────────────────────────────────────────────

# Base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libjpeg-dev libfreetype6-dev zip unzip libonig-dev \
    libxml2-dev libzip-dev nginx supervisor postgresql-client libpq-dev \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite mbstring zip exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Remove cached config to ensure fresh config on deploy
RUN rm -f bootstrap/cache/config.php

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set up Laravel permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN mkdir -p /var/www/html/database && chown -R www-data:www-data /var/www/html/database || true

# Copy Nginx config
COPY ./nginx.conf /etc/nginx/sites-available/default

# Expose port
EXPOSE 80

# Start Supervisor to run both Nginx and PHP-FPM
# Copy helper entrypoint that waits for the DB and runs migrations
COPY ./docker-entrypoint.sh /var/www/html/docker-entrypoint.sh
RUN chmod +x /var/www/html/docker-entrypoint.sh

# Use the entrypoint script to wait for the DB, run migrations, and start supervisor
ENTRYPOINT ["sh", "/var/www/html/docker-entrypoint.sh"]
