
# Stage 0: use Composer image to install dependencies
FROM composer:2 AS composer

# Stage 1: PHP-FPM runtime
FROM php:8.2-fpm

# Install OS packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Copy Composer binary from builder stage
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Mark the working directory as a safe Git directory
RUN git config --global --add safe.directory /var/www/html

# Allow Composer plugins to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Run Symfony auto-scripts (cache clear, assets install, etc.)
RUN composer run-script auto-scripts

# Ensure the var and vendor directories are owned by www-data
RUN chown -R www-data:www-data var vendor

# Expose FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
