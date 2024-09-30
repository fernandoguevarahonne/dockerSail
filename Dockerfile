FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    default-mysql-client \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./ /var/www

RUN chown -R www-data:www-data /var/www

COPY nginx.conf /etc/nginx/conf.d/default.conf

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]