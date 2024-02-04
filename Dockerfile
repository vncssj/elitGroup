FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y libpq-dev \
    git \
    unzip \
    vim \
    libonig-dev \
    libxml2-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql dom json \
    && docker-php-ext-enable dom json

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# RUN docker-php-ext-install mbstring

# RUN docker-php-ext-install xml

# RUN pecl install pcov \
# && docker-php-ext-enable pcov

# RUN pecl install xdebug && docker-php-ext-enable xdebug

# RUN composer require --dev phpunit/phpunit ^9.6


RUN a2enmod rewrite
