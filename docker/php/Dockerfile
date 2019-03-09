FROM php:7.0-fpm-alpine

#Update
RUN apk update && apk upgrade

#Install custom dependencies
RUN apk --no-cache add nano mc bash gcc libtool autoconf g++ make pcre-dev re2c file

# Install dependencies
RUN apk --no-cache add postgresql-dev

# Build and install PHP extensions
RUN docker-php-ext-install -j$(nproc) pdo && \
    docker-php-ext-install -j$(nproc) pdo_pgsql && \
    docker-php-ext-install -j$(nproc) pcntl

#Phalcon extension
ADD phalcon.so /usr/lib/php7/modules/phalcon.so
RUN cp /usr/lib/php7/modules/phalcon.so $(php -r 'echo ini_get("extension_dir");')
RUN docker-php-ext-enable phalcon

# Eliminar cosas que ya no necesitamos :D
RUN rm -fr /var/lib/apt/lists/* && \
    rm -fr /tmp/* /var/tmp/*