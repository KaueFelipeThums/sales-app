FROM php:8.3-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    git \
    zip \
    wget \
    curl \
    unzip \
    zlib1g-dev \
 && docker-php-ext-install pdo pdo_mysql zip xml opcache intl \
 && docker-php-source delete

# Instalação do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ativa o módulo de rewrite do Apache
RUN a2enmod rewrite

# Define timezone do sistema
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Configuração do Apache e PHP
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
COPY apache.conf /etc/apache2/conf-enabled/zzzcustom.conf
COPY php.ini /usr/local/etc/php/conf.d/zzzcustom.ini

# Define permissões do diretório da aplicação
RUN chown -R www-data:www-data /var/www/html

# Define diretório de trabalho
WORKDIR /var/www/html
