FROM php:8.2-apache

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

RUN mkdir -p data storage/users
RUN chown -R www-data:www-data data storage
RUN chmod -R 755 data storage

RUN echo "upload_max_filesize=50M" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/uploads.ini
