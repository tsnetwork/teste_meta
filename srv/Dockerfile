FROM php:7.4-apache


COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /usr/local/etc/php/php.ini

#Configuração do Xdebug
RUN pecl install -f xdebug && docker-php-ext-enable xdebug

RUN echo "xdebug.remote_autostart=1" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.remote_enable=1" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.remote_host=172.17.0.1" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null

#Habilitar do Apache Rewrite
RUN a2enmod rewrite
RUN service apache2 restart

#Extensões necessárias
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install exif

RUN apt-get update && \
    apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev 

RUN docker-php-ext-install gd

RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/

RUN docker-php-ext-install -j$(nproc) gd


    
RUN service apache2 restart

RUN chmod 777 -R /var/www/html

