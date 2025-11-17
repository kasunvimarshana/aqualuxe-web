FROM php:7.4-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    libzip-dev \
    libmcrypt-dev \
    libmemcached-dev \
    imagemagick-dev \
    nginx \
    supervisor

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo_mysql \
    intl \
    zip \
    bcmath \
    opcache

# Install PECL extensions
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install imagick && docker-php-ext-enable imagick

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wp

# Create a user for the application
RUN addgroup -g 1000 -S app && \
    adduser -u 1000 -S app -G app

# Set working directory
WORKDIR /var/www/html

# Copy custom php.ini
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Set permissions
RUN chown -R app:app /var/www/html

# Switch to non-root user
USER app