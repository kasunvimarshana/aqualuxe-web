# AquaLuxe WordPress Theme - Production Docker Image
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install essential system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    freetype-dev \
    git \
    icu-dev \
    libc-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip

# Configure and install PHP extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    mysqli \
    opcache \
    pdo_mysql \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP for production
COPY docker/php/php.ini /usr/local/etc/php/conf.d/aqualuxe.ini

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]