FROM php:7.3.3-fpm

RUN apt-get update && apt-get install -y git curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y libzip-dev

RUN docker-php-ext-install zip

RUN pecl install xdebug-2.7.0 \
    && docker-php-ext-enable xdebug

RUN mkdir -p /.composer && chown 1000:1000 /.composer

USER 1000

WORKDIR /app
