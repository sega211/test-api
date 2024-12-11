
FROM php:8.1-fpm


RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip libzip-dev libxml2-dev


RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo pdo_mysql opcache xml


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


WORKDIR /var/www


RUN rm -rf /var/www/*





RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache


EXPOSE 9000
CMD ["php-fpm"]


