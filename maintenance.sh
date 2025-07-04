#!/bin/bash

# Script de Mantenimiento para Laravel - Bash
# Sistema DEIC - Mantenimiento automatizado
# Uso: ./maintenance.sh [opcion]

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

function show_help() {
    echo -e "${GREEN}=== SCRIPT DE MANTENIMIENTO LARAVEL ===${NC}"
    echo ""
    echo -e "${YELLOW}Uso: ./maintenance.sh [opcion]${NC}"
    echo ""
    echo -e "${CYAN}Opciones disponibles:${NC}"
    echo "  cache-clear    - Limpiar todas las caches"
    echo "  cache-rebuild  - Reconstruir todas las caches"
    echo "  optimize       - Optimizar aplicacion para produccion"
    echo "  dev-setup      - Configurar entorno de desarrollo"
    echo "  events-check   - Verificar eventos y listeners"
    echo "  db-refresh     - Refrescar base de datos con seeders"
    echo "  permissions    - Arreglar permisos de archivos"
    echo "  composer-update - Actualizar dependencias"
    echo "  full-clean     - Limpieza completa del sistema"
    echo "  help           - Mostrar esta ayuda"
    echo ""
}

function clear_all_caches() {
    echo -e "${GREEN}=== LIMPIANDO CACHES ===${NC}"
    
    echo -e "${YELLOW}Limpiando cache de aplicacion...${NC}"
    php artisan cache:clear
    
    echo -e "${YELLOW}Limpiando cache de configuracion...${NC}"
    php artisan config:clear
    
    echo -e "${YELLOW}Limpiando cache de rutas...${NC}"
    php artisan route:clear
    
    echo -e "${YELLOW}Limpiando cache de vistas...${NC}"
    php artisan view:clear
    
    echo -e "${YELLOW}Limpiando cache de eventos...${NC}"
    php artisan event:clear
    
    echo -e "${GREEN}Cache limpiado exitosamente!${NC}"
}

function rebuild_all_caches() {
    echo -e "${GREEN}=== RECONSTRUYENDO CACHES ===${NC}"
    
    clear_all_caches
    
    echo -e "${YELLOW}Generando cache de configuracion...${NC}"
    php artisan config:cache
    
    echo -e "${YELLOW}Generando cache de rutas...${NC}"
    php artisan route:cache
    
    echo -e "${YELLOW}Generando cache de eventos...${NC}"
    php artisan event:cache
    
    echo -e "${GREEN}Caches reconstruidos exitosamente!${NC}"
}

function optimize_production() {
    echo -e "${GREEN}=== OPTIMIZANDO PARA PRODUCCION ===${NC}"
    
    echo -e "${YELLOW}Instalando dependencias de produccion...${NC}"
    composer install --optimize-autoloader --no-dev
    
    echo -e "${YELLOW}Optimizando autoloader...${NC}"
    composer dump-autoload --optimize
    
    rebuild_all_caches
    
    echo -e "${GREEN}Optimizacion completada!${NC}"
}

function setup_development() {
    echo -e "${GREEN}=== CONFIGURANDO ENTORNO DE DESARROLLO ===${NC}"
    
    echo -e "${YELLOW}Instalando dependencias de desarrollo...${NC}"
    composer install
    
    echo -e "${YELLOW}Generando clave de aplicacion...${NC}"
    php artisan key:generate
    
    clear_all_caches
    
    echo -e "${YELLOW}Ejecutando migraciones...${NC}"
    php artisan migrate
    
    echo -e "${GREEN}Entorno de desarrollo configurado!${NC}"
}

function check_events() {
    echo -e "${GREEN}=== VERIFICANDO EVENTOS Y LISTENERS ===${NC}"
    
    echo -e "${YELLOW}Lista de eventos registrados:${NC}"
    php artisan event:list
    
    echo -e "${YELLOW}Limpiando cache de eventos...${NC}"
    php artisan event:clear
    
    echo -e "${YELLOW}Regenerando cache de eventos...${NC}"
    php artisan event:cache
    
    echo -e "${GREEN}Verificacion de eventos completada!${NC}"
}

function refresh_database() {
    echo -e "${GREEN}=== REFRESCANDO BASE DE DATOS ===${NC}"
    
    echo -e "${RED}ADVERTENCIA: Esto eliminara todos los datos!${NC}"
    read -p "Continuar? (y/N): " confirm
    
    if [[ $confirm == "y" || $confirm == "Y" ]]; then
        echo -e "${YELLOW}Refrescando migraciones...${NC}"
        php artisan migrate:refresh
        
        echo -e "${YELLOW}Ejecutando seeders...${NC}"
        php artisan db:seed
        
        echo -e "${GREEN}Base de datos refrescada!${NC}"
    else
        echo -e "${YELLOW}Operacion cancelada.${NC}"
    fi
}

function fix_permissions() {
    echo -e "${GREEN}=== ARREGLANDO PERMISOS ===${NC}"
    
    echo -e "${YELLOW}Arreglando permisos de storage...${NC}"
    if [ -d "storage" ]; then
        chmod -R 775 storage
        chown -R www-data:www-data storage 2>/dev/null || true
    fi
    
    echo -e "${YELLOW}Arreglando permisos de bootstrap/cache...${NC}"
    if [ -d "bootstrap/cache" ]; then
        chmod -R 775 bootstrap/cache
        chown -R www-data:www-data bootstrap/cache 2>/dev/null || true
    fi
    
    echo -e "${YELLOW}Arreglando permisos de archivos de logs...${NC}"
    if [ -d "storage/logs" ]; then
        chmod -R 664 storage/logs/*.log 2>/dev/null || true
    fi
    
    echo -e "${GREEN}Permisos arreglados!${NC}"
}

function update_composer() {
    echo -e "${GREEN}=== ACTUALIZANDO DEPENDENCIAS ===${NC}"
    
    echo -e "${YELLOW}Actualizando composer...${NC}"
    composer self-update
    
    echo -e "${YELLOW}Actualizando dependencias...${NC}"
    composer update
    
    echo -e "${YELLOW}Optimizando autoloader...${NC}"
    composer dump-autoload --optimize
    
    echo -e "${GREEN}Dependencias actualizadas!${NC}"
}

function full_clean() {
    echo -e "${GREEN}=== LIMPIEZA COMPLETA DEL SISTEMA ===${NC}"
    
    clear_all_caches
    
    echo -e "${YELLOW}Limpiando logs...${NC}"
    if [ -d "storage/logs" ]; then
        find storage/logs -name "*.log" -type f -delete 2>/dev/null || true
    fi
    
    echo -e "${YELLOW}Limpiando archivos temporales...${NC}"
    if [ -d "storage/framework/cache/data" ]; then
        rm -rf storage/framework/cache/data/* 2>/dev/null || true
    fi
    
    echo -e "${YELLOW}Limpiando sessions...${NC}"
    if [ -d "storage/framework/sessions" ]; then
        rm -rf storage/framework/sessions/* 2>/dev/null || true
    fi
    
    echo -e "${YELLOW}Limpiando compiled views...${NC}"
    if [ -d "storage/framework/views" ]; then
        rm -rf storage/framework/views/* 2>/dev/null || true
    fi
    
    echo -e "${YELLOW}Optimizando autoloader...${NC}"
    composer dump-autoload --optimize
    
    echo -e "${GREEN}Limpieza completa finalizada!${NC}"
}

# Verificar que estamos en un proyecto Laravel
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: No se encontro el archivo artisan. Asegurate de estar en la raiz del proyecto Laravel.${NC}"
    exit 1
fi

# Procesar argumentos
case "${1:-help}" in
    "cache-clear")
        clear_all_caches
        ;;
    "cache-rebuild")
        rebuild_all_caches
        ;;
    "optimize")
        optimize_production
        ;;
    "dev-setup")
        setup_development
        ;;
    "events-check")
        check_events
        ;;
    "db-refresh")
        refresh_database
        ;;
    "permissions")
        fix_permissions
        ;;
    "composer-update")
        update_composer
        ;;
    "full-clean")
        full_clean
        ;;
    "help"|*)
        show_help
        ;;
esac
