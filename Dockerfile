FROM php:8.3-apache

# Esta linha ensina o Docker a instalar o MySQLi
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Esta linha copia seus arquivos
COPY . /var/www/html/