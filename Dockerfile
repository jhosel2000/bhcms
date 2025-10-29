# ────────────────────────────────────────────────
# Dockerfile for Laravel + PostgreSQL + PHP 8.2 + Nginx
# ────────────────────────────────────────────────

# Base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libjpeg-dev libfreetype6-dev zip unzip libonig-dev \
    libxml2-dev libzip-dev nginx supervisor \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set up Laravel permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx config
COPY ./nginx.conf /etc/nginx/sites-available/default

# Expose port
EXPOSE 80

# Start Supervisor to run both Nginx and PHP-FPM
CMD php artisan migrate --force && \
    php artisan storage:link && \
    supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
