FROM php:7.4-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar los archivos de la aplicación al contenedor
COPY ./web /var/www/html/

# Habilitar módulos de Apache si es necesario
RUN a2enmod rewrite
