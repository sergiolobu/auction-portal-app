FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get update && \
apt-get install -y \
libzip-dev

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

RUN  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer


WORKDIR /usr/src/app

COPY --chown=1000:1000 apps/auction-portal-app /usr/src/app

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin