#!/bin/bash
set -e

echo "🚀 Iniciando contenedor en Render..."

# 1. Cachear configuración y rutas para producción
echo "⚙️  Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Ejecutar migraciones y seeders (La base de datos debe estar lista)
echo "📦 Ejecutando migraciones y seeders de base de datos..."
php artisan migrate --force
php artisan db:seed --force

# 3. Iniciar Apache en primer plano
echo "🔥 Iniciando Apache..."
exec apache2-foreground
