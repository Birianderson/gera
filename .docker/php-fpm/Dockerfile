FROM php:8.2-fpm

MAINTAINER Adriano Colombo <colombo@tce.mt.gov.br>

# Add user for laravel application
RUN useradd -u 1000 -ms /bin/bash -g www-data www


#Install dependencies
RUN apt-get update
RUN apt-get install -y software-properties-common build-essential ca-certificates libpng-dev libjpeg62-turbo-dev libfreetype6-dev locales zip jpegoptim optipng pngquant gifsicle unzip git curl lua-zlib-dev libmemcached-dev libbz2-dev alien libaio1 wget

#Repositorio
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions zip
RUN install-php-extensions exif
RUN install-php-extensions pcntl
RUN install-php-extensions gd
RUN install-php-extensions memcached
RUN install-php-extensions bz2
RUN install-php-extensions xdebug
RUN install-php-extensions readline
RUN install-php-extensions ldap
RUN install-php-extensions oci8
RUN install-php-extensions pdo_oci
RUN install-php-extensions sqlite3
RUN install-php-extensions imagick
RUN install-php-extensions soap
RUN install-php-extensions bcmath
RUN install-php-extensions redis
RUN install-php-extensions memcached
RUN install-php-extensions pcov
RUN install-php-extensions curl
RUN install-php-extensions imap
RUN install-php-extensions xml

#composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

COPY --chown=www:www-data php.ini /usr/local/etc/php/php.ini
COPY --chown=www:www-data www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

EXPOSE 9000
