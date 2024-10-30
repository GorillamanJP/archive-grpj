FROM php:fpm

RUN apt-get update && apt-get install git zlib1g-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev -y

RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql

RUN git config --global --add safe.directory /var/www
