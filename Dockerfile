FROM php:8.2-apache

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio raíz
WORKDIR /var/www/html
COPY . .

# COPIAR TODO LO DE PANIS-CO A LA RAÍZ (INCLUYENDO OCULTOS) Y BORRAR LA CARPETA REPETIDA
RUN cp -r panis-co/. . && rm -rf panis-co panis-co-laravel.zip

# Instalar dependencias en la raíz ya organizada
RUN composer install --no-dev --optimize-autoloader

# Configurar Apache directo a la raíz pública estándar
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]