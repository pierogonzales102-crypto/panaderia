#!/usr/bin/env bash
# Salir inmediatamente si un comando falla
set -o errexit

# Instalar las dependencias de Laravel en el servidor
composer install --no-dev --optimize-autoloader

# Limpiar y optimizar la caché de Laravel para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache