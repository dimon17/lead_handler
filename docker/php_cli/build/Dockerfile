FROM php:7.4.21-zts

LABEL maintainer="Dmitriy <hondalom@gmail.com>"

COPY config/php.ini /usr/local/etc/php/

# Extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libcurl4-gnutls-dev \
    libbz2-dev \
    zlib1g-dev \
    libpng-dev \
    libpq-dev \
    libssl-dev \
    libc-client-dev \
    libkrb5-dev \
    libxml2-dev \
    libmcrypt-dev \
    libmemcached-dev \
    libgmp-dev \
    libreadline-dev \
    libonig-dev \
    msmtp \
    wget \
    libzip-dev \
    zip \
    unzip \
    git \
    htop \
    procps

RUN pecl install \
        memcached \
        mcrypt-1.0.4 \
        mongodb \
        redis \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install -j$(nproc) imap \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) bz2 \
         dom \
         curl \
         fileinfo \
         gd \
         gmp \
         iconv \
         bcmath \
         mysqli \
         mbstring \
         pcntl \
         pdo \
         pdo_mysql \
         pdo_pgsql \
         posix \
         zip \
         sockets \
         soap \
    && docker-php-ext-enable \
         opcache \
         mongodb \
         redis \
         memcached \
    && apt-get clean -y && apt-get autoclean -y && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install parallel && \
    docker-php-ext-enable parallel && \
    EXTENSION_DIR=`php-config --extension-dir 2>/dev/null` && \
    echo "=====================================================================" && \
    echo "Finished building. SHA256:" && \
    sha256sum "$EXTENSION_DIR/parallel.so" | sed "s/  /\n/" && \
    echo "====================================================================="


# Composer
RUN php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && chmod +x composer.phar \
    && mv composer.phar /usr/bin/composer

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Add global composer bin dir to PATH
ENV PATH /root/.composer/vendor/bin:$PATH

WORKDIR /application