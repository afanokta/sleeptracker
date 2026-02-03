# =========================
# Build stage
# =========================
FROM php:8.3-fpm-alpine AS build

# System dependencies
RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        intl \
        zip \
        exif \
        pcntl \
        gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first (cache)
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Copy application
COPY . .

# Optimize Laravel
RUN php artisan optimize \
 && chown -R www-data:www-data storage bootstrap/cache

# =========================
# Runtime stage
# =========================
FROM php:8.3-fpm-alpine

# Runtime libs only
RUN apk add --no-cache \
    icu \
    oniguruma \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype

# PHP extensions (runtime)
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    exif \
    pcntl \
    gd

WORKDIR /var/www

COPY --from=build /var/www /var/www

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
