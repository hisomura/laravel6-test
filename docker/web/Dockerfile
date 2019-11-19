FROM php:7.3.11-apache
WORKDIR /
RUN \
    apt-get update && \
    apt-get install -y git libxml2-dev libzip-dev zip unzip && \
    apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev libfreetype6-dev && \
    git clone https://github.com/vishnubob/wait-for-it.git && \
    rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
        --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir \
        --enable-gd-native-ttf
RUN docker-php-ext-install pdo_mysql mbstring tokenizer xml zip gd
RUN yes | pecl install xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY core.ini /usr/local/etc/php/conf.d/core.ini

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite
RUN rm /etc/apache2/mods-enabled/auth_basic.load

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN mkdir -p /var/www/project
WORKDIR /var/www/project
