FROM php:apache

WORKDIR /var/www/html

RUN chown -R www-data:www-data .

ARG DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y --fix-missing \
    apt-utils \
    gnupg

RUN echo "deb http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN echo "deb-src http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN curl -sS --insecure https://www.dotdeb.org/dotdeb.gpg | apt-key add -

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev
RUN docker-php-ext-install zip

RUN a2enmod expires
RUN a2enmod deflate
RUN a2enmod rewrite

COPY .docker/rauchfuss-io.conf /etc/apache2/sites-available
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN a2ensite rauchfuss-io
RUN a2dissite 000-default

COPY . .

RUN composer install