FROM php:7.3.3-fpm

RUN apt-get update && apt-get install -y git curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER 1000

WORKDIR /app
