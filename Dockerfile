FROM php:8.0-apache
# install composer

RUN apt-get update && apt-get install -y git zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer require jwilsson/spotify-web-api-php

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

# Disable php error reporting
RUN sed -i 's/display_errors = On/display_errors = Off/g' /usr/local/etc/php/php.ini-production


CMD ["apache2-foreground"]


