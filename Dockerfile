FROM php:apache

WORKDIR /src

COPY . .
COPY .docker/rauchfuss-io.conf /etc/apache2/sites-available

RUN a2ensite rauchfuss-io
RUN a2dissite 000-default

RUN a2enmod expires
RUN a2enmod deflate

RUN chown -R www-data:www-data .