FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

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

# Copy existing application directory
COPY . /var/www/symfony

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/symfony 