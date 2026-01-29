FROM php:8.2-apache

# 1. Instalar dependencias del sistema y librerías necesarias
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# 2. Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# 3. Configurar DocumentRoot de Apache a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Instalar Node.js y NPM (para compilar assets con Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# 6. Configurar directorio de trabajo
WORKDIR /var/www/html

# 7. Copiar archivos del proyecto
COPY . /var/www/html

# 8. Instalar dependencias de PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 9. Instalar dependencias de Node y compilar assets
RUN npm install && npm run build

# 10. Ajustar permisos para que Apache pueda escribir en storage y cache
RUN mkdir -p /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/cache \
    /var/www/html/bootstrap/cache

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Copiar y preparar script de entrada
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 12. Exponer puerto 80
EXPOSE 80

# 13. Definir entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
