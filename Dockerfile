# Etapa 1: Build com Composer
FROM composer:2 as build

WORKDIR /app

# Copia apenas os arquivos essenciais para instalar dependências
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist

# Etapa 2: Imagem com PHP
FROM php:8.2-cli

# Instala extensões necessárias do PHP
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Define diretório de trabalho
WORKDIR /var/www

# Copia arquivos da build e do projeto
COPY --from=build /app/vendor /var/www/vendor
COPY . /var/www

# Permissão para storage e bootstrap
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Expõe a porta 80
EXPOSE 80

# Comando para iniciar o Laravel na porta 80 (serve escutando 0.0.0.0)
CMD php artisan serve --host=0.0.0.0 --port=80
