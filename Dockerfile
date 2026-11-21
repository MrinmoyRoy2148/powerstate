FROM php:8.4-apache AS php-base

RUN apt-get update && apt-get install -y \
    git unzip pkg-config libonig-dev libzip-dev libicu-dev libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring bcmath exif pcntl zip intl \
    && a2enmod rewrite headers \
    && sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Composer dependencies
FROM php-base AS vendor
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Build Laravel/Vite frontend assets
FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Final web image
FROM php-base AS final
COPY . .
COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p storage/framework/cache storage/framework/sessions \
    storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000
CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]