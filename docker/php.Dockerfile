FROM php:8.5-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    icu-dev \
    g++ \
    make \
    autoconf \
    postgresql-dev  # <--- Library PostgreSQL untuk Alpine

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql gd zip intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www