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

# Directorio de trabajo: Entramos directo a donde están tus archivos reales
WORKDIR /var/www/html
COPY . .

# CAMBIO CLAVE: Nos movemos a la subcarpeta donde está tu Laravel de verdad
WORKDIR /var/www/html/panis-co

# Instalar dependencias dentro de la carpeta del proyecto
RUN composer install --no-dev --optimize-autoloader

# Configurar Apache para apuntar exactamente a la carpeta pública interna
ENV APACHE_DOCUMENT_ROOT /var/www/html/panis-co/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Permisos de almacenamiento correctos internos
RUN chown -R www-data:www-data /var/www/html/panis-co/storage /var/www/html/panis-co/bootstrap/cache

CMD ["apache2-foreground"]