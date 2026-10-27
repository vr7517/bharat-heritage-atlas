# syntax=docker/dockerfile:1.4

# ==========================================
# Stage 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:22-alpine AS frontend
WORKDIR /app

# Copy dependency specifications and install cleanly
COPY package.json package-lock.json ./
RUN npm ci --no-audit --prefer-offline

# Copy source assets and configuration files
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY tailwind.config.js* ./

# Build compiled assets into public/build
RUN npm run build

# ==========================================
# Stage 2: Vendor Dependencies (Composer)
# ==========================================
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

# ==========================================
# Stage 3: Production Runtime (PHP 8.3 + Nginx)
# ==========================================
FROM php:8.3-fpm-bookworm AS production

LABEL maintainer="Bharat Heritage Atlas <dev@bharatheritage.org>"
LABEL fly_launch_runtime="laravel"

# Avoid interactive apt prompts
ENV DEBIAN_FRONTEND=noninteractive

# Install System Binaries, Nginx, Supervisor, SQLite3 & Build Libraries
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    curl \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_sqlite \
        zip \
        intl \
        gd \
        bcmath \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy application source code
COPY . /var/www/html

# Copy compiled frontend assets from Stage 1
COPY --from=frontend /app/public/build /var/www/html/public/build

# Copy vendor dependencies from Stage 2
COPY --from=vendor /app/vendor /var/www/html/vendor

# Remove any stale bootstrap caches and discover production packages
RUN rm -f /var/www/html/bootstrap/cache/*.php \
    && php /var/www/html/artisan package:discover --ansi

# Copy Fly & Server Configuration Files
COPY .fly/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .fly/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .fly/php.ini /usr/local/etc/php/conf.d/99-production.ini
COPY .fly/entrypoint.sh /entrypoint.sh

# Ensure entrypoint has Unix line endings, is executable, and setup directory ownership
RUN sed -i 's/\r$//' /entrypoint.sh \
    && chmod +x /entrypoint.sh \
    && mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache /run/nginx /run/php \
    && chown -R www-data:www-data /var/www/html /run/nginx /run/php

# Expose web server port
EXPOSE 8080

# Configure healthcheck using Laravel 11/12/13 /up route
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://127.0.0.1:8080/up || exit 1

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
