FROM php:8.1-fpm

# Instalacja wymaganych narzędzi
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

# Ustawienia katalogu roboczego
WORKDIR /var/www/html

# Skopiowanie plików projektu do kontenera
COPY . .

# Instalacja Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalacja zależności PHP
RUN composer install

CMD ["php-fpm"]
