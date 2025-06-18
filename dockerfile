FROM composer:2 AS builder

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-suggest

COPY . .

FROM php:8.1-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

RUN sed -ri \
    -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
    -e 's!<Directory /var/www/>!<Directory /var/www/html/public>!g' \
    /etc/apache2/sites-available/000-default.conf

COPY --from=builder /app /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
