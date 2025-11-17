## Introducción

Este repositorio contiene la API de un proyecto construida con Laravel 12. Sigue una arquitectura orientada a servicios con DTOs, repositorios y recursos. Aquí encontrarás instrucciones para montar un entorno de desarrollo simple y para desplegar la aplicación en un servidor Linux.

## Entorno de desarrollo
Sigue estos pasos para levantar la API rápidamente en tu máquina local (Linux/WSL o macOS). Esta guía usa SQLite para un arranque rápido sin depender de MySQL.

1. Clona el repositorio:

```bash
git clone https://github.com/trazzos/apideic.git
cd apideic
```

2. Instala dependencias PHP

```bash
composer install
```

3. Copia `.env` y configura SQLite:

```bash
cp .env.example .env
mkdir -p database
touch database/database.sqlite
# luego en .env pon:
# DB_CONNECTION=sqlite
# DB_DATABASE=/full/path/to/apideic/database/database.sqlite
```

4. Genera la clave y ejecuta migraciones + seeders:

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DatabaseSeeder
```

5. Levanta el servidor local:

```bash
php artisan serve
```

> Si prefieres usar MySQL en lugar de SQLite, ajusta las variables `DB_*` en `.env` y ejecuta las mismas migraciones.

---

## Despliegue y configuración de BD en produccion (Servidor compartido / dedicado)

A continuación se describen pasos claros para desplegar esta API Laravel en:

- Hosting compartido con acceso SSH
- Servidor dedicado (VPS, DigitalOcean, AWS EC2, etc.)

Todos los pasos asumen que tienes acceso por SSH y que Composer está disponible en el servidor.

### Requisitos previos

- PHP 8.0+ con extensiones necesarias (pdo, mbstring, BCMath, OpenSSL, tokenizer, XML, ctype, json, fileinfo, etc.)
- Composer
- Base de datos soportada (MySQL, MariaDB, PostgreSQL) accesible desde la app

### Resumen de pasos comunes

1. Clona o sube el repositorio al servidor

2. Instala dependencias PHP:

```bash
composer install --no-dev --optimize-autoloader
```

3. Copia el archivo de ejemplo de entorno y configúralo:

```bash
cp .env.example .env
```

Rellena la configuración de DB, APP_URL y otros valores de producción en `.env`.

Notas:

>Por default al autenticarse por session, el nombre del cookie se contruye concantenando APP_NAME_ + 'session .

>Si requieres un nombre especifico agrega en .env la variable SESSION_COOKIE con el nombre que desees.

>Lo que elijas recuerda configurar la variable de entorno de frontend con el mismo nombre de la cookie, ya que es requerido para la autenticacion.

4. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

5. Ajusta permisos (Linux):

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

6. Crea el enlace simbólico a `storage`:

```bash
php artisan storage:link
```

7. Optimiza cachés:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Hosting compartido (con SSH)

1. Sube los archivos y entra en la carpeta del proyecto
2. Ejecuta `composer install` como se mostró arriba
3. Configura `.env` y genera la clave
4. Ejecuta migraciones y seeders (ver orden abajo)

```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
```

### Servidor dedicado (recomendado)

En un servidor dedicado puedes ejecutar los mismos comandos y además configurar servicios y mecanismos de despliegue continuo.

Comandos para producción:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Reinicia PHP-FPM y el servidor web si haces cambios de configuración.

### Orden y ejecución de seeders

El seeder principal es `DatabaseSeeder`. Ejecutarlo aplica los seeders del proyecto en el orden correcto:

1. `InicializarPermisos` — crea roles y permisos (Spatie)
2. `InicializarDatosPrueba` — carga datos iniciales (secretarías, direcciones, departamentos, etc.)

Comando para ejecutar la semilla principal:

```bash
php artisan db:seed --class=DatabaseSeeder
```

Si prefieres correr seeders individuales:

```bash
php artisan db:seed --class=InicializarPermisos
php artisan db:seed --class=InicializarDatosPrueba
```

### Seguridad y post-deploy

- Asegúrate de que `.env` no sea accesible desde la web y que el document root apunte a `public/` únicamente
- Mantén `APP_DEBUG=false` en producción
- Controla los límites de subida en `php.ini` y la configuración del servidor web

### Configurar Document Root (Apache / Nginx)

Es crítico que el `DocumentRoot` del servidor web apunte al directorio `public` de este proyecto. Esto evita que archivos sensibles (como `.env`) estén expuestos y permite que Laravel maneje correctamente las peticiones.

Ejemplos de configuración:

- Apache (VirtualHost)

```apacheconf
<VirtualHost *:80>
    ServerName ejemplo.com
    DocumentRoot /var/www/apideic/public

    <Directory /var/www/apideic/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/apideic_error.log
    CustomLog ${APACHE_LOG_DIR}/apideic_access.log combined
    # Asegúrate de tener habilitado mod_rewrite
</VirtualHost>
```

- Nginx (server block)

```nginx
server {
    listen 80;
    server_name ejemplo.com;
    root /var/www/apideic/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock; # Ajusta la versión de PHP
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Después de modificar la configuración reinicia el servidor web:

```bash
sudo systemctl restart apache2    # para Apache
sudo systemctl restart nginx      # para Nginx
```

### Rollback y reinstalación desde cero

Para recrear la base de datos desde cero (peligro: borra datos):

```bash
php artisan migrate:fresh --seed
```