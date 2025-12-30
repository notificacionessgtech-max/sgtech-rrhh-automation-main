#!/bin/bash

# Esperar a que MySQL esté listo
echo "⏳ Esperando a MySQL..."
until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" &> /dev/null; do
  sleep 2
done
echo "✅ MySQL listo."

# Instalar dependencias PHP si no existen
if [ ! -d "vendor" ]; then
  echo "📦 Instalando dependencias de PHP..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "📦 Dependencias PHP ya instaladas."
fi

# Instalar dependencias JS si no existen
if [ ! -d "node_modules" ]; then
  echo "📦 Instalando dependencias de Node..."
  npm install
fi

echo "⚙️ Compilando assets con Vite..."
npm run build

# Limpiar cachés previos (opcional si sabes que vienen limpios)
echo "🧹 Limpiando cachés antiguos..."
php artisan optimize:clear

# Migraciones
echo "🧱 Ejecutando migraciones..."
php artisan migrate --force

echo "🧱 Ejecutando los seeders..."
php artisan db:seed

# Optimización para producción
echo "🚀 Cacheando configuración, rutas, vistas y eventos..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Dump autoload optimizado
composer dump-autoload -o

# Iniciar PHP-FPM
exec "$@"
