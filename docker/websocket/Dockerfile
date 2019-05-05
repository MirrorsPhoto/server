FROM php:7.3-cli-alpine

# Build and install PHP extensions
RUN docker-php-ext-install -j$(nproc) pcntl

# Eliminar cosas que ya no necesitamos :D
RUN rm -fr /var/lib/apt/lists/* && \
    rm -fr /tmp/* /var/tmp/*

CMD [ "php", "websocket_server.php", "start" ]