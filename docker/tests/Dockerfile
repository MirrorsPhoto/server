FROM php:7.3-cli-alpine

RUN echo http://mirror.yandex.ru/mirrors/alpine/v3.9/main > /etc/apk/repositories; \
	echo http://mirror.yandex.ru/mirrors/alpine/v3.9/community >> /etc/apk/repositories

#Update
RUN apk update && apk upgrade

#Install custom dependencies
RUN apk --no-cache add nano mc bash

# Install dependencies
RUN apk --no-cache add postgresql-dev

# Build and install PHP extensions
RUN docker-php-ext-install -j$(nproc) pgsql

#Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Eliminar cosas que ya no necesitamos :D
RUN rm -fr /var/lib/apt/lists/* && \
    rm -fr /tmp/* /var/tmp/*