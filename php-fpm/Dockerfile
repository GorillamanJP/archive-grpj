FROM php:fpm

WORKDIR /var/www

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install git zip unzip -y
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get install -y libgmp-dev
RUN docker-php-ext-install gmp

RUN apt-get install zlib1g-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev -y

RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql

RUN git config --global --add safe.directory /var/www

RUN chown -R www-data:www-data /var/www

USER www-data