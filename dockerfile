FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libssl-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    # Instalar la extensión de MongoDB a través de PECL
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Limpiar caché de apt para reducir el tamaño de la imagen
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Limpiar cualquier caché de configuración que pueda existir
RUN php artisan config:clear

# Configurar permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Copiar configuración de Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Exponer puerto
EXPOSE 8080

# Script de inicio
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]