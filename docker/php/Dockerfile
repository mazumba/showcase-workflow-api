FROM arm64v8/php:8.3-fpm-alpine

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk add mysql-client git libzip-dev

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apk add icu-dev \
    && docker-php-ext-install bcmath intl pdo_mysql zip

# mysqldump -h mariadb -u root -p root db_name > dump.sql