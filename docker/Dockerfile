FROM php:alpine

#
# Scripts build time
#
COPY ./scripts/* /usr/local/bin/

RUN set -xeuo pipefail \
    && docker-php-install-composer \
    && apk add gmp-dev gmp linux-headers \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-install gmp pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del gmp-dev .build-deps
