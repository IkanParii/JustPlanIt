# ==========================================
# STAGE 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:20-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm ci || npm install

COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY public ./public

RUN npm run build

# ==========================================
# STAGE 2: PHP 8.2-FPM Production
# ==========================================
FROM php:8.2-fpm-alpine AS app

# Install dependencies sistem & ekstensi PHP
RUN apk add --no-cache \
    bash \
    curl \
    fcgi \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        zip \
        gd \
        opcache \
        bcmath \
        intl

# Ambil binary Composer resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin source code project
COPY . .

# Salin asset yang sudah dicompile dari node_builder
COPY --from=node_builder /app/public/build ./public/build

# Install dependensi PHP untuk production (tanpa dev package)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Salin konfigurasi PHP & PHP-FPM
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Healthcheck service PHP-FPM
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
    CMD cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000

USER www-data

CMD ["php-fpm"]
