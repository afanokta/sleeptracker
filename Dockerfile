FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev libicu-dev \ libpng-dev libjpeg-dev libfreetype6-dev \
    RUN apt-get update && apt-get install -y \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pgsql pdo pdo_pgsql mbstring xml intl zip gd opc

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/

COPY . .
EXPOSE 9000
CMD ["php-fpm"]