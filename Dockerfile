FROM php:8.2-apache

WORKDIR /var/www/html
COPY . .

# Comando de diagnóstico: Listar todo en la consola de Render
RUN echo "=== CONTENIDO DE LA RAÍZ ===" && ls -la && echo "=== CONTENIDO DE PUBLIC ===" && ls -la public || true

CMD ["apache2-foreground"]