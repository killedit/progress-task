FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first for better cache
COPY laravel/composer.json composer.json
COPY laravel/composer.lock composer.lock

# Disable artisan scripts during build (they need env)
RUN composer install --no-scripts --no-dev --optimize-autoloader --prefer-dist

# Copy the rest of the Laravel application
COPY laravel/ .

# Copy PHP config
COPY docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Ensure proper permissions
RUN chown -R www-data:www-data /var/www/html

# RUN chmod -R 775 storage bootstrap/cache

# RUN php artisan migrate --force && \
#     php artisan db:seed --force

EXPOSE 9000
CMD ["php-fpm"]
