# Set the base image for subsequent instructions
FROM php:7.1

# Update packages
RUN apt-get update

# Install gnupg
RUN apt-get install -y gnupg

# Install php-imagick
RUN apt-get install php-imagick

# Install ext-gd
RUN apt-get install php7.1-gd

# Install PHP and composer dependencies
RUN apt-get install -qq git curl libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev

# Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get install -y nodejs

# Clear out the local repository of retrieved package files
RUN apt-get clean

# Install needed extensions
# Here you can install any other extension that you need during the test and deployment process
RUN docker-php-ext-install mcrypt pdo_mysql zip

#xdebug
RUN pecl install xdebug-2.6.1 && docker-php-ext-enable xdebug
COPY php.ini-development usr/local/etc/php/php.ini-development

# Install Composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel Envoy
RUN composer global require "laravel/envoy=~1.0"