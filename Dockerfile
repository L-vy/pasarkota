FROM composer:2.6 as builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts --prefer-dist --ignore-platform-reqs
COPY . .

FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

ENV PORT=8000

RUN docker-php-ext-install pdo_mysql

COPY --from=builder /app /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000
CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --class=ProductSeeder --force && php artisan serve --host=0.0.0.0 --port=8000"]