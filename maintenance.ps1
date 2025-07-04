# Script de Mantenimiento para Laravel - PowerShell
# Sistema DEIC - Mantenimiento automatizado
# Uso: .\maintenance.ps1 [opcion]

param(
    [string]$Action = "help"
)

function Show-Help {
    Write-Host "=== SCRIPT DE MANTENIMIENTO LARAVEL ===" -ForegroundColor Green
    Write-Host ""
    Write-Host "Uso: .\maintenance.ps1 [opcion]" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Opciones disponibles:" -ForegroundColor Cyan
    Write-Host "  cache-clear    - Limpiar todas las caches"
    Write-Host "  cache-rebuild  - Reconstruir todas las caches"
    Write-Host "  optimize       - Optimizar aplicacion para produccion"
    Write-Host "  dev-setup      - Configurar entorno de desarrollo"
    Write-Host "  events-check   - Verificar eventos y listeners"
    Write-Host "  db-refresh     - Refrescar base de datos con seeders"
    Write-Host "  permissions    - Arreglar permisos de archivos"
    Write-Host "  composer-update - Actualizar dependencias"
    Write-Host "  full-clean     - Limpieza completa del sistema"
    Write-Host "  help           - Mostrar esta ayuda"
    Write-Host ""
}

function Clear-AllCaches {
    Write-Host "=== LIMPIANDO CACHES ===" -ForegroundColor Green
    
    Write-Host "Limpiando cache de aplicacion..." -ForegroundColor Yellow
    php artisan cache:clear
    
    Write-Host "Limpiando cache de configuracion..." -ForegroundColor Yellow
    php artisan config:clear
    
    Write-Host "Limpiando cache de rutas..." -ForegroundColor Yellow
    php artisan route:clear
    
    Write-Host "Limpiando cache de vistas..." -ForegroundColor Yellow
    php artisan view:clear
    
    Write-Host "Limpiando cache de eventos..." -ForegroundColor Yellow
    php artisan event:clear
    
    Write-Host "Cache limpiado exitosamente!" -ForegroundColor Green
}

function Rebuild-AllCaches {
    Write-Host "=== RECONSTRUYENDO CACHES ===" -ForegroundColor Green
    
    Clear-AllCaches
    
    Write-Host "Generando cache de configuracion..." -ForegroundColor Yellow
    php artisan config:cache
    
    Write-Host "Generando cache de rutas..." -ForegroundColor Yellow
    php artisan route:cache
    
    Write-Host "Generando cache de eventos..." -ForegroundColor Yellow
    php artisan event:cache
    
    Write-Host "Caches reconstruidos exitosamente!" -ForegroundColor Green
}

function Optimize-Production {
    Write-Host "=== OPTIMIZANDO PARA PRODUCCION ===" -ForegroundColor Green
    
    Write-Host "Instalando dependencias de produccion..." -ForegroundColor Yellow
    composer install --optimize-autoloader --no-dev
    
    Write-Host "Optimizando autoloader..." -ForegroundColor Yellow
    composer dump-autoload --optimize
    
    Rebuild-AllCaches
    
    Write-Host "Optimizacion completada!" -ForegroundColor Green
}

function Setup-Development {
    Write-Host "=== CONFIGURANDO ENTORNO DE DESARROLLO ===" -ForegroundColor Green
    
    Write-Host "Instalando dependencias de desarrollo..." -ForegroundColor Yellow
    composer install
    
    Write-Host "Generando clave de aplicacion..." -ForegroundColor Yellow
    php artisan key:generate
    
    Clear-AllCaches
    
    Write-Host "Ejecutando migraciones..." -ForegroundColor Yellow
    php artisan migrate
    
    Write-Host "Entorno de desarrollo configurado!" -ForegroundColor Green
}

function Check-Events {
    Write-Host "=== VERIFICANDO EVENTOS Y LISTENERS ===" -ForegroundColor Green
    
    Write-Host "Lista de eventos registrados:" -ForegroundColor Yellow
    php artisan event:list
    
    Write-Host "Limpiando cache de eventos..." -ForegroundColor Yellow
    php artisan event:clear
    
    Write-Host "Regenerando cache de eventos..." -ForegroundColor Yellow
    php artisan event:cache
    
    Write-Host "Verificacion de eventos completada!" -ForegroundColor Green
}

function Refresh-Database {
    Write-Host "=== REFRESCANDO BASE DE DATOS ===" -ForegroundColor Green
    
    Write-Host "ADVERTENCIA: Esto eliminara todos los datos!" -ForegroundColor Red
    $confirm = Read-Host "Continuar? (y/N)"
    
    if ($confirm -eq "y" -or $confirm -eq "Y") {
        Write-Host "Refrescando migraciones..." -ForegroundColor Yellow
        php artisan migrate:refresh
        
        Write-Host "Ejecutando seeders..." -ForegroundColor Yellow
        php artisan db:seed
        
        Write-Host "Base de datos refrescada!" -ForegroundColor Green
    } else {
        Write-Host "Operacion cancelada." -ForegroundColor Yellow
    }
}

function Fix-Permissions {
    Write-Host "=== ARREGLANDO PERMISOS ===" -ForegroundColor Green
    
    Write-Host "Arreglando permisos de storage..." -ForegroundColor Yellow
    if (Test-Path "storage") {
        icacls "storage" /grant "Everyone:(OI)(CI)F" /T
    }
    
    Write-Host "Arreglando permisos de bootstrap/cache..." -ForegroundColor Yellow
    if (Test-Path "bootstrap/cache") {
        icacls "bootstrap/cache" /grant "Everyone:(OI)(CI)F" /T
    }
    
    Write-Host "Permisos arreglados!" -ForegroundColor Green
}

function Update-Composer {
    Write-Host "=== ACTUALIZANDO DEPENDENCIAS ===" -ForegroundColor Green
    
    Write-Host "Actualizando composer..." -ForegroundColor Yellow
    composer self-update
    
    Write-Host "Actualizando dependencias..." -ForegroundColor Yellow
    composer update
    
    Write-Host "Optimizando autoloader..." -ForegroundColor Yellow
    composer dump-autoload --optimize
    
    Write-Host "Dependencias actualizadas!" -ForegroundColor Green
}

function Full-Clean {
    Write-Host "=== LIMPIEZA COMPLETA DEL SISTEMA ===" -ForegroundColor Green
    
    Clear-AllCaches
    
    Write-Host "Limpiando logs..." -ForegroundColor Yellow
    if (Test-Path "storage/logs/*.log") {
        Remove-Item "storage/logs/*.log" -Force
    }
    
    Write-Host "Limpiando archivos temporales..." -ForegroundColor Yellow
    if (Test-Path "storage/framework/cache/data/*") {
        Remove-Item "storage/framework/cache/data/*" -Force -Recurse
    }
    
    Write-Host "Limpiando sessions..." -ForegroundColor Yellow
    if (Test-Path "storage/framework/sessions/*") {
        Remove-Item "storage/framework/sessions/*" -Force
    }
    
    Write-Host "Optimizando autoloader..." -ForegroundColor Yellow
    composer dump-autoload --optimize
    
    Write-Host "Limpieza completa finalizada!" -ForegroundColor Green
}

# Ejecutar accion basada en parametro
switch ($Action.ToLower()) {
    "cache-clear" { Clear-AllCaches }
    "cache-rebuild" { Rebuild-AllCaches }
    "optimize" { Optimize-Production }
    "dev-setup" { Setup-Development }
    "events-check" { Check-Events }
    "db-refresh" { Refresh-Database }
    "permissions" { Fix-Permissions }
    "composer-update" { Update-Composer }
    "full-clean" { Full-Clean }
    "help" { Show-Help }
    default { 
        Write-Host "Opcion no valida: $Action" -ForegroundColor Red
        Show-Help 
    }
}
