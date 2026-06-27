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

WORKDIR /var/www/html
COPY . .

# SI LA CARPETA PANIS-CO SIGUE FASTIDIANDO, SACAMOS TODO DE AHÍ A LA FUERZA
RUN if [ -d "panis-co" ]; then cp -r panis-co/. . && rm -rf panis-co; fi

# Instalar dependencias en limpio
RUN composer install --no-dev --optimize-autoloader

# APUNTAR APACHE DIRECTO A LA CARPETA PUBLIC QUE YA ESTÁ EN LA RAÍZ
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# PERMISOS DE ACCESO TOTALES (Para que no vuelva a salir Forbidden)
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]