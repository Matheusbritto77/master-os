# Etapa 1: Build com Composer usando mesma base PHP 8.2
FROM php:8.2-cli AS build

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist

# Etapa 2: Apache com PHP 8.2
FROM php:8.2-apache

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Ativar mod_rewrite do Apache
RUN a2enmod rewrite

# Permitir .htaccess
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Apontar Apache para a pasta /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copia dependências do Composer
COPY --from=build /app/vendor /var/www/html/vendor

# Copia código-fonte
COPY . /var/www/html

# Permissões corretas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Apache será iniciado automaticamente
