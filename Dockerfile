FROM php:8.2-apache

# Instala extensiones necesarias para Laravel y MySQL
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia todos los archivos de tu proyecto
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Copia archivo de configuración personalizado de Apache
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80
EXPOSE 80
