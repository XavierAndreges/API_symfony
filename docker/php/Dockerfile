FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    acl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP-FPM
RUN sed -i '/^listen =/d' /usr/local/etc/php-fpm.d/www.conf && \
    echo "listen = 9000" >> /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /var/www/symfony

# Copy composer files first to leverage Docker cache
COPY symfony/composer.json symfony/composer.lock symfony/symfony.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY symfony/ .

# Create empty .env file to prevent Symfony from looking for it
RUN touch .env

# Create necessary directories and set permissions
RUN mkdir -p var/cache var/log var/sessions \
    && chown -R www-data:www-data var/cache var/log var/sessions \
    && chmod -R 777 var/cache var/log var/sessions \
    && chmod -R 755 /var/www/symfony

# Generate autoloader
RUN composer dump-autoload --optimize

EXPOSE 9000

CMD ["php-fpm"]