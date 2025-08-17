# Use official PHP 8.2 FPM image as base
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    postgresql-dev \
    postgresql-client \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    opcache

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copy application code
COPY . .

# Set proper ownership and permissions
RUN chown -R www:www /var/www/html
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache
RUN chmod -R 777 /var/www/html/storage/framework/views
RUN chmod -R 777 /var/www/html/storage/framework/cache
RUN chmod -R 777 /var/www/html/storage/framework/sessions

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create required directories
RUN mkdir -p /var/log/supervisor
RUN mkdir -p /run/nginx

# Create entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# DO NOT cache config during build - it will be done at runtime with proper env vars

# Switch to non-root user
USER www

# Expose port 80
EXPOSE 80

# Switch back to root for supervisor
USER root

# Start supervisor
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]