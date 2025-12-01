#!/bin/bash

# Salir inmediatamente si un comando falla.
set -e

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Ejecutar migraciones (opcional)
php artisan migrate --force

# Iniciar Nginx
nginx -g 'daemon off;'
```

**3. En Render, selecciona:**
- **Language**: Docker
- **Branch**: tu rama principal (main/master)
- **Region**: la que prefieras

**4. Variables de entorno** (agrégalas después en Render):
```
APP_ENV=production
APP_KEY=base64:tu-key-aqui
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=tu-host
DB_DATABASE=tu-database
DB_USERNAME=tu-usuario
DB_PASSWORD=tu-contraseña