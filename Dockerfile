# =========================
# Build stage
# =========================
FROM php:8.3-fpm-alpine AS build

RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    exif \
    pcntl \
    gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

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

# COPY PHP extensions from build
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /var/www
COPY --from=build /var/www /var/www

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
