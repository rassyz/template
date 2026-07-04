FROM php:8.4-fpm

ENV PS1="\u@\h:\w\\$ "
ENV TZ="Asia/Jakarta"
ENV COMPOSER_MEMORY_LIMIT="-1"

RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils \
    ca-certificates \
    curl \
    git \
    unzip \
    zip \
    cron \
    nano \
    iputils-ping \
    telnet \
    netcat-openbsd \
    net-tools \
    openssh-server \
    libmemcached-dev \
    libreadline-dev \
    libgmp-dev \
    libzip-dev \
    zlib1g-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libwebp-dev \
    libssl-dev \
    libmagickwand-dev \
    libxml2-dev \
    libicu-dev \
    libonig-dev \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions required by Laravel / Filament and common projects
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        bcmath \
        exif \
        gd \
        gmp \
        intl \
        mbstring \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        soap \
        zip \
        opcache

# PECL extensions
RUN pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN pecl install memcached \
    && docker-php-ext-enable memcached

# Install Xdebug but do not enable it by default.
# docker-entrypoint.sh will enable/disable it based on XDEBUG=true/false.
RUN pecl install xdebug

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Laravel Schedule Cron Job
RUN echo "* * * * * root /usr/local/bin/php /var/www/html/artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel-scheduler \
    && chmod 0644 /etc/cron.d/laravel-scheduler

# Local PHP config, if available
COPY ./local.ini /usr/local/etc/php/conf.d/local.ini

# Useful aliases
RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/html/artisan "$@"' > /usr/bin/art \
    && chmod +x /usr/bin/art

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/html/artisan migrate "$@"' > /usr/bin/migrate \
    && chmod +x /usr/bin/migrate

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/html/artisan migrate:fresh --seed' > /usr/bin/fresh \
    && chmod +x /usr/bin/fresh

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/html/artisan config:clear\n/var/www/html/vendor/bin/phpunit -d memory_limit=2G --stop-on-error --stop-on-failure --testdox-text=tests/report.txt "$@"' > /usr/bin/t \
    && chmod +x /usr/bin/t

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/html/artisan config:clear\n/bin/bash\n/usr/local/bin/php /var/www/html/artisan dusk -d memory_limit=2G --stop-on-error --stop-on-failure --testdox-text=tests/report-dusk.txt "$@"' > /usr/bin/d \
    && chmod +x /usr/bin/d

WORKDIR /var/www/html

COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && ln -sf /usr/local/bin/docker-entrypoint.sh /docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]