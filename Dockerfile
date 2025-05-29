# Etapa 1: Build com Composer
FROM composer:2 AS build

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist

# Etapa 2: PHP com Apache
FROM php:8.2-apache

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Ativar reescrita no Apache
RUN a2enmod rewrite

# Configurar Apache para permitir .htaccess
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Define o diretório de trabalho do Apache
WORKDIR /var/www/html

# Copia dependências instaladas com o Composer
COPY --from=build /app/vendor /var/www/html/vendor

# Copia o restante do projeto para a pasta pública do Apache
COPY . /var/www/html

# Permissões corretas para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expõe a porta padrão do Apache
EXPOSE 80

# Apache já é iniciado automaticamente no container base
