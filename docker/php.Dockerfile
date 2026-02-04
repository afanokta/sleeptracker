FROM php:8.5-fpm-alpine

# Install system dependencies & PostgreSQL dev tools
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
    postgresql-dev

# Install PHP extensions (PDO Postgres & Postgres)
RUN docker-php-ext-install pdo_pgsql pgsql gd zip intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www