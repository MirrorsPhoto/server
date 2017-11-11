FROM phalconphp/php-apache:ubuntu-16.04

RUN apt-get update
RUN apt-get install -y mc

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime
RUN "date"

COPY entrypoint.sh /entrypoint.cmd