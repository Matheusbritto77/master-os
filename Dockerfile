# Etapa 1: Builder com Composer e dependências de build
FROM php:8.2-cli AS build

# Instala dependências do sistema necessárias para Composer
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copia os arquivos do Composer
COPY composer.json composer.lock ./

# Instala as dependências do PHP
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist

# Etapa 2: Container final com Apache
FROM php:8.2-apache

# Copia as extensões do PHP da etapa anterior
RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev unzip zip git \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Copia os arquivos e dependências da etapa build
COPY --from=build /app /var/www/html

# Copia configurações adicionais (opcional)
# COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Define permissões (opcional)
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80
