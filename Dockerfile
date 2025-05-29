FROM php:8.2-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip curl zip libzip-dev libonig-dev libpng-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath

# Instalar Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Ativar mod_rewrite do Apache
RUN a2enmod rewrite

# Clonar o repositório do projeto
RUN git clone https://github.com/Matheusbritto77/master-os.git /var/www/html

# Definir diretório de trabalho
WORKDIR /var/www/html

# Instalar dependências do Laravel (sem dev para produção)
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts

# Permissões para o Laravel funcionar corretamente
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Substituir o vhost padrão para apontar para /public
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Expõe a porta 80
EXPOSE 80
