# syntax = docker/dockerfile:1.2
FROM php:8.0-fpm-bullseye as code-base

RUN apt update \
 && apt install -y git patch unzip libzip-dev libtidy-dev libicu-dev libpng-dev \
    libjpeg-dev libfreetype6-dev libyaml-dev libonig-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) mysqli pdo_mysql zip tidy intl pcntl mbstring ctype gd \
 && pecl channel-update pecl.php.net && pecl install yaml \
 && docker-php-ext-enable yaml \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && echo "date.timezone=Europe/Kyiv" > "$PHP_INI_DIR/conf.d/timezone.ini"

RUN echo "zend_extension=opcache.so" >> "$PHP_INI_DIR/php.ini" \
 && echo "opcache.enable_cli=1" >> "$PHP_INI_DIR/php.ini" \
 && echo "opcache.enable=1" >> "$PHP_INI_DIR/php.ini" \
 && echo "opcache.jit_buffer_size=32M" >> "$PHP_INI_DIR/php.ini" \
 && echo "opcache.jit=1235" >> "$PHP_INI_DIR/php.ini"

RUN docker-php-source delete

COPY --from=composer:2.1.11 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
CMD ["php-fpm"]

COPY [ "composer.json", "composer.lock", "/app/" ]
RUN composer check-platform-reqs --no-dev

#####
FROM code-base as code-dev

#####
FROM code-base as code-tests

RUN apt update \
 && pecl install pcov && docker-php-ext-enable pcov \
 && echo "pcov.enabled = 1" >> "$PHP_INI_DIR/php.ini" \
 && echo "pcov.directory = ." >> "$PHP_INI_DIR/php.ini"