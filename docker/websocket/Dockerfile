FROM php:7.3-cli-alpine

RUN echo http://mirror.yandex.ru/mirrors/alpine/v3.9/main > /etc/apk/repositories; \
	echo http://mirror.yandex.ru/mirrors/alpine/v3.9/community >> /etc/apk/repositories

#Update
RUN apk update && apk upgrade

# Build and install PHP extensions
RUN docker-php-ext-install -j$(nproc) pcntl

# Eliminar cosas que ya no necesitamos :D
RUN rm -fr /var/lib/apt/lists/* && \
    rm -fr /tmp/* /var/tmp/*

CMD [ "php", "websocket_server.php", "start" ]