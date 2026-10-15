FROM php:8.4-apache

# Install dependencies yang sering dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    iputils-ping \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (Pdo Mysql, Mbstring, Exif, PCNTL, Bcmath, GD)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# mod_rewrite WAJIB aktif supaya .htaccess diproses Apache
RUN a2enmod rewrite

# DocumentRoot permanen ke folder public/ Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
WORKDIR /var/www/html

# Ganti VirtualHost default dengan konfigurasi eksplisit yang:
#   1. mengarahkan DocumentRoot ke /var/www/html/public
#   2. memakai AllowOverride All agar .htaccess dibaca (routing Laravel)
# Lalu pastikan site-nya aktif.
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default

EXPOSE 80
