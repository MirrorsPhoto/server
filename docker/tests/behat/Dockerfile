FROM php:7.0-cli-alpine

#Install custom dependencies
RUN apk --no-cache add nano mc bash

# Install dependencies
RUN apk --no-cache add postgresql-dev

# Build and install PHP extensions
RUN docker-php-ext-install -j$(nproc) pgsql

# Eliminar cosas que ya no necesitamos :D
RUN rm -fr /var/lib/apt/lists/* && \
    rm -fr /tmp/* /var/tmp/*

#CMD ["php", "-v"]