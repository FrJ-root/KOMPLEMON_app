FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip git curl \
    libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

RUN git config --global --add safe.directory /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
