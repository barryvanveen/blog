FROM composer:2 AS composer
FROM php:8.0-fpm-bullseye

WORKDIR /var/www/app

ARG NODE_VERSION=16

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y libzip-dev

RUN docker-php-ext-install bcmath pdo_mysql zip

ARG xdebug

RUN if [ ${xdebug} = true ]; then \
        pecl install xdebug \
        && docker-php-ext-enable xdebug; \
    fi

RUN curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
RUN apt-get install -y nodejs
# this line will enable yarn
RUN corepack enable

COPY php-configuration.ini /usr/local/etc/php/conf.d/php-configuration.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
