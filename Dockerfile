FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql

RUN a2enmod rewrite