FROM php:8

RUN docker-php-ext-install -j$(nproc) pdo_mysql

CMD php /app/repro.php
