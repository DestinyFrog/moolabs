FROM php:8.2-apache

WORKDIR /var/www/html

EXPOSE 80

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

CMD ["apache2-foreground"]