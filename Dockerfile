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

# Configurar el directorio de trabajo principal
WORKDIR /var/www/html
COPY . .

# Descomprimir el proyecto
RUN if [ -f Panaderia.zip ]; then unzip -o Panaderia.zip && rm Panaderia.zip; fi

# TRUCO DEFINITIVO: Si los archivos quedaron dentro de una carpeta, los saca a la raíz principal
RUN find . -maxdepth 2 -name "composer.json" -exec dirname {} \; | xargs -I {} mv {}/* . 2>/dev/null || true

# Instalar dependencias en la raíz
RUN composer install --no-dev --optimize-autoloader

# Configurar Apache para apuntar a la carpeta pública de la raíz
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]