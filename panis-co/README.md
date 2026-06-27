# Panis & Co — Sistema de Gestión Laravel

Aplicación web integral para la panadería y pastelería artesanal **Panis & Co**, desarrollada según el informe de funcionalidades Laravel.

## Módulos Implementados

| Módulo | Funcionalidades |
|--------|----------------|
| **Autenticación** | Registro (CUS01), login (CUS02), perfiles, roles con Spatie Permission |
| **Catálogo** | Consulta de productos (CUS03), filtros, búsqueda, gestión admin |
| **Pedidos** | Pedido estándar (CUS04), personalizado (CUS05), carrito (CUS06), seguimiento |
| **Pagos** | Métodos de pago (CUS07), transacciones, comprobantes PDF |
| **Producción** | Planificación, órdenes de producción, recetas |
| **Inventario** | Control de insumos, alertas de stock bajo, proveedores, compras |
| **Ventas** | POS presencial, reportes de ventas con gráficos |

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (incluido) o MySQL

## Instalación

```bash
# 1. Extraer el proyecto y entrar al directorio
cd panis-co

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Base de datos (SQLite incluido)
php artisan migrate:fresh --seed

# 5. Enlace de storage para imágenes
php artisan storage:link

# 6. Compilar assets
npm run build

# 7. Iniciar servidor
php artisan serve
```

Visita: http://localhost:8000

## Usuarios de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@panisandco.com | password |
| Ventas | ventas@panisandco.com | password |
| Producción | produccion@panisandco.com | password |
| Cliente | cliente@panisandco.com | password |

## Códigos de Descuento

- `PANIS10` — 10% de descuento
- `DULCE15` — 15% de descuento
- `HORNO20` — 20% de descuento

## Paleta de Colores (UI)

Interfaz moderna con tonos marrones, beige y acentos dorados:
- Marrón oscuro: `#3E2723`
- Marrón medio: `#5D4037`
- Dorado: `#C9A227` / `#B8860B`
- Crema: `#FAF6F0`

## Stack Tecnológico

- Laravel 12 + Breeze (Blade)
- Tailwind CSS
- Spatie Laravel Permission
- DomPDF (comprobantes)
- SQLite / MySQL

## Estructura de Roles

- **administrador/gerente**: Acceso completo
- **ventas/moderador_web**: Pedidos, POS, catálogo
- **produccion**: Órdenes de producción, recetas
- **cliente**: Catálogo, carrito, pedidos propios

---

Desarrollado según *Informe Técnico de Funcionalidades para la Aplicación Laravel: Panis & Co*.
