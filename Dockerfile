FROM php:apache

COPY src /var/www/html

RUN a2enmod expires
RUN a2enmod deflate