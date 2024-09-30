FROM php:fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install git -y

RUN git config --global --add safe.directory /var/www