FROM php:7.4-fpm-buster

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN chmod +x /usr/bin/wait-for-it

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:schema:create ;  php-fpm

EXPOSE 9000
