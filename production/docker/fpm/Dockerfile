# Stage 1: Base image for both development and build
FROM php:8.3-fpm AS base

# Install system dependencies
RUN apt-get update && \
    apt-get install -y zlib1g-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev libcurl4-openssl-dev libzip-dev openssl zip unzip build-essential curl libpq-dev && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install exif gd zip pdo_mysql bcmath curl mbstring zip pcntl pdo_pgsql

RUN pecl install redis && docker-php-ext-enable redis

# Install and enable APCu extension
RUN pecl install apcu && \
    docker-php-ext-enable apcu

# Stage 2: Development stage
FROM base AS dev

# Copy necessary files for development
COPY composer.json composer.lock /var/www/html/
COPY app /var/www/html/app
COPY bootstrap /var/www/html/bootstrap
COPY config /var/www/html/config
COPY artisan /var/www/html/artisan

# Stage 3: Build stage
FROM base AS build-fpm

# Set working directory
WORKDIR /var/www/html

# Install Composer from composer:2.5 image
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Copy application files and install dependencies
COPY composer.json composer.lock /var/www/html/
COPY .env.production /var/www/html/.env
RUN composer install --prefer-dist --no-ansi --no-dev --no-autoloader
COPY artisan /var/www/html/artisan
COPY bootstrap /var/www/html/bootstrap
COPY app /var/www/html/app
COPY config /var/www/html/config
COPY routes /var/www/html/routes

# Copy the rest of the application
COPY . /var/www/html/

# Generate optimized autoload files
RUN composer dump-autoload -o

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs


COPY package.json package-lock.json /var/www/html/

# Install dependencies and build assets
RUN npm ci
RUN npm run build

# Stage 4: Final stage for running the application
FROM base AS fpm

# Copy application files from the build-fpm stage
COPY --from=build-fpm /var/www/html /var/www/html

# Copy custom PHP-FPM configuration
# COPY ./production/docker/fpm/custom-www.conf /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000
CMD [ "php-fpm" ]
