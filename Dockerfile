# ---- Composer (build vendor) ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# ---- Node (build assets) ----
FROM node:20 AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# ---- Runtime: PHP + Apache with required extensions ----
FROM php:8.2-apache

# Install system libs and PHP extensions (intl, zip, pdo_mysql)
RUN apt-get update && apt-get install -y \
    libicu-dev zlib1g-dev libzip-dev unzip git \
 && docker-php-ext-install intl zip pdo pdo_mysql \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy app source
COPY . .

# Copy vendor from composer stage and built assets from node stage
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Set Apache DocumentRoot to /public
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# Storage symlink (ignore if already exists)
RUN php artisan storage:link || true

# Cache config/routes/views for prod
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
EXPOSE 80
CMD ["apache2-foreground"]
