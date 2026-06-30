#!/usr/bin/env bash
# Script de despliegue automatizado para Laravel en Render
# Salir inmediatamente si algun comando falla
set -e

echo " Iniciando proceso de despliegue en Render..."

echo " Instalar dependencias de PHP (production)..."
composer install --no-dev --optimize-autoloader
+
echo " Limpiando configuraciones antiguas..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
+
echo " Optimizando cach ede Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Ejecutar las migraciones en la base de datos de Render
echo " Ejecutando migraciones de la base de datos..."
php artisan migrate --force

echo " Despliegue completado con exito en Render!"