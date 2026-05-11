FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git curl zip unzip libpng-dev libjpeg-turbo-dev freetype-dev \
    libzip-dev icu-dev postgresql-dev oniguruma-dev \
    nodejs npm postgresql-client

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip intl

# Increase PHP upload limits
RUN echo 'upload_max_filesize = 60M' > /usr/local/etc/php/conf.d/uploads.ini \
    && echo 'post_max_size = 65M' >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo 'memory_limit = 256M' >> /usr/local/etc/php/conf.d/uploads.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for Docker layer caching)
COPY composer.json composer.lock* ./

# Install dependencies (production only)
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

# Copy application
COPY . .

# Run post-install scripts after source is available
RUN composer dump-autoload --optimize --no-interaction 2>/dev/null || true

# Create required storage directories
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port (Render assigns PORT dynamically)
EXPOSE 8000

# Use entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]
