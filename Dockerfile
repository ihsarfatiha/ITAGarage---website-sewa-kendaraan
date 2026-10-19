# syntax=docker/dockerfile:1

# ============================================================
#  TAHAP 1 - Build asset frontend (CSS/JS) pakai Vite
# ============================================================
FROM node:20-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

# ============================================================
#  TAHAP 2 - Aplikasi: PHP 8.2 + Apache
# ============================================================
FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
        libicu-dev libpq-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath exif gd intl opcache pcntl pdo_mysql pdo_pgsql zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Composer dipakai untuk install dependency PHP
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Dependency PHP (layer ini cuma di-rebuild kalau composer.lock berubah)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist \
        --no-scripts --no-autoloader

# Kode aplikasi
COPY . .

# Hasil build Vite dari tahap 1 (tidak ada di git karena di-.gitignore)
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --no-dev --optimize --no-interaction \
    && mkdir -p bootstrap/cache \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
CMD ["apache2-foreground"]
