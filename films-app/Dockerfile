FROM php:7.2.12-fpm-stretch

RUN apt-get update

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

ARG WEB_USER_ID=33
ARG WEB_USER_NAME=www-data
RUN echo "Building for web user: id=${WEB_USER_ID} name=${WEB_USER_NAME}"
RUN useradd -m -u ${WEB_USER_ID} ${WEB_USER_NAME} || echo "User exists, it's ok."
RUN sed -i -- "s/user = www-data/user = ${WEB_USER_NAME}/g" /usr/local/etc/php-fpm.d/www.conf

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/usr/bin --filename=composer \
  && php -r "unlink('composer-setup.php');"
# Create dir for composer cache
RUN mkdir /var/www/.composer && chown "${WEB_USER_ID}" /var/www/.composer

RUN apt-get install git unzip -y

RUN pecl install xdebug && docker-php-ext-enable xdebug

USER ${WEB_USER_ID}
