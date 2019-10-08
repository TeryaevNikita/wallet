FROM composer:latest AS composer
FROM php:7.2-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev

RUN apt-get install git -y

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Postgre Client
RUN mkdir -p /usr/share/man/man1 \
    && mkdir -p /usr/share/man/man7 \\
    && apt-get install -y postgresql-client

RUN docker-php-ext-install zip

RUN apt-get install -y gnupg2
RUN rm -rf /var/lib/apt/lists/ && curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get install nodejs -y

COPY . /var/www/html/payment.local

WORKDIR /var/www/html/payment.local

RUN ls
RUN cp .env.example .env

RUN composer install

RUN php artisan key:generate

EXPOSE 8000
