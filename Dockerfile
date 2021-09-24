FROM php:8.0.11-apache

WORKDIR /var/www/html

RUN a2enmod expires
RUN a2enmod deflate
RUN a2enmod rewrite

COPY .docker/rauchfuss-io.conf /etc/apache2/sites-available

RUN a2ensite rauchfuss-io
RUN a2dissite 000-default

USER www-data
COPY . .

USER root