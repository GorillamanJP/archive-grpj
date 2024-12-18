FROM php:fpm

WORKDIR /var/www

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install git zip unzip -y
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get install -y libgmp-dev
RUN docker-php-ext-install gmp

RUN composer require minishlink/web-push

RUN apt-get install zlib1g-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev -y

RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql

RUN git config --global --add safe.directory /var/www

RUN apt-get install -y openssl
RUN openssl ecparam -name prime256v1 -genkey -noout -out /tmp/vapid_private_key.pem
RUN openssl ec -in /tmp/vapid_private_key.pem -pubout -out /tmp/vapid_public_key.pem

RUN openssl ec -in /tmp/vapid_private_key.pem -outform DER | base64 -w 0 > /var/www/.vapid_private_key
RUN openssl ec -in /tmp/vapid_public_key.pem -pubout -outform DER | base64 -w 0 > /var/www/.vapid_public_key
