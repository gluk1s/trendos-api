FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Install Composer inside the container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html