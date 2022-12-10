FROM ubuntu:22.04 AS base

# Install dependencies
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    ca-certificates \
    curl \
    git \
    vim \
    ssh \
    gcc \
    g++ \
    make \
    unzip \
    libpq-dev \
    php8.1-fpm \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-pgsql \
    php8.1-curl \
    nginx

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

WORKDIR /var/www

# Copy project code and install project dependencies
COPY composer.json composer.lock ./
RUN composer install --no-autoloader

# Copy project configurations
COPY --chown=root ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY --chown=root ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY --chown=root docker_run.sh /docker_run.sh

###

FROM node:18 AS assets

WORKDIR /var/www

COPY package.json package-lock.json ./
RUN npm install

COPY resources/ ./resources
COPY vite.config.ts tsconfig.json ./
COPY --from=base /var/www/vendor ./vendor

RUN npm run build

###

FROM base

COPY --from=assets /var/www/public ./public
COPY . .

RUN rm .env*
COPY .env.prod .env

RUN composer dumpautoload
RUN php artisan storage:link
RUN php artisan key:generate --force
RUN php artisan optimize

RUN chown -R www-data storage bootstrap/cache

EXPOSE 80

# Start command
CMD sh /docker_run.sh
