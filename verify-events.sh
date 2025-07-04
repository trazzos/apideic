#!/bin/bash

# Script para verificar eventos del sistema DEIC
# Uso: ./verify-events.sh

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}=== VERIFICACION DE EVENTOS DEL SISTEMA DEIC ===${NC}"
echo ""

echo -e "${YELLOW}1. Verificando estructura de eventos...${NC}"
echo "TareaCompletedStatusChanged:"
if [ -f "app/Events/TareaCompletedStatusChanged.php" ]; then
    echo -e "  ${GREEN}✓ Encontrado${NC}"
else
    echo -e "  ${RED}✗ No encontrado${NC}"
fi

echo "ActividadCompletedStatusChanged:"
if [ -f "app/Events/ActividadCompletedStatusChanged.php" ]; then
    echo -e "  ${GREEN}✓ Encontrado${NC}"
else
    echo -e "  ${RED}✗ No encontrado${NC}"
fi

echo ""
echo -e "${YELLOW}2. Verificando listeners...${NC}"
echo "UpdateActividadStatusOnTareaChange:"
if [ -f "app/Listeners/UpdateActividadStatusOnTareaChange.php" ]; then
    echo -e "  ${GREEN}✓ Encontrado${NC}"
else
    echo -e "  ${RED}✗ No encontrado${NC}"
fi

echo "UpdateProyectoStatusOnActividadChange:"
if [ -f "app/Listeners/UpdateProyectoStatusOnActividadChange.php" ]; then
    echo -e "  ${GREEN}✓ Encontrado${NC}"
else
    echo -e "  ${RED}✗ No encontrado${NC}"
fi

echo ""
echo -e "${YELLOW}3. Listando eventos registrados en Laravel...${NC}"
php artisan event:list

echo ""
echo -e "${YELLOW}4. Verificando sintaxis de archivos...${NC}"

echo "Verificando eventos:"
php -l app/Events/TareaCompletedStatusChanged.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "  TareaCompletedStatusChanged: ${GREEN}✓ OK${NC}"
else
    echo -e "  TareaCompletedStatusChanged: ${RED}✗ Error de sintaxis${NC}"
fi

php -l app/Events/ActividadCompletedStatusChanged.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "  ActividadCompletedStatusChanged: ${GREEN}✓ OK${NC}"
else
    echo -e "  ActividadCompletedStatusChanged: ${RED}✗ Error de sintaxis${NC}"
fi

echo "Verificando listeners:"
php -l app/Listeners/UpdateActividadStatusOnTareaChange.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "  UpdateActividadStatusOnTareaChange: ${GREEN}✓ OK${NC}"
else
    echo -e "  UpdateActividadStatusOnTareaChange: ${RED}✗ Error de sintaxis${NC}"
fi

php -l app/Listeners/UpdateProyectoStatusOnActividadChange.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "  UpdateProyectoStatusOnActividadChange: ${GREEN}✓ OK${NC}"
else
    echo -e "  UpdateProyectoStatusOnActividadChange: ${RED}✗ Error de sintaxis${NC}"
fi

echo ""
echo -e "${YELLOW}5. Verificando registro en AppServiceProvider...${NC}"
if grep -q "TareaCompletedStatusChanged" app/Providers/AppServiceProvider.php; then
    echo -e "  TareaCompletedStatusChanged registrado: ${GREEN}✓ OK${NC}"
else
    echo -e "  TareaCompletedStatusChanged registrado: ${RED}✗ No encontrado${NC}"
fi

if grep -q "ActividadCompletedStatusChanged" app/Providers/AppServiceProvider.php; then
    echo -e "  ActividadCompletedStatusChanged registrado: ${GREEN}✓ OK${NC}"
else
    echo -e "  ActividadCompletedStatusChanged registrado: ${RED}✗ No encontrado${NC}"
fi

echo ""
echo -e "${GREEN}=== VERIFICACION COMPLETADA ===${NC}"