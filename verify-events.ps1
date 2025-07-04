# Script para verificar eventos del sistema DEIC - PowerShell
# Uso: .\verify-events.ps1

function Test-EventFile {
    param([string]$FilePath, [string]$Description)
    
    if (Test-Path $FilePath) {
        Write-Host "  $Description`: " -NoNewline
        Write-Host "OK" -ForegroundColor Green
        return $true
    } else {
        Write-Host "  $Description`: " -NoNewline
        Write-Host "No encontrado" -ForegroundColor Red
        return $false
    }
}

function Test-PhpSyntax {
    param([string]$FilePath, [string]$Description)
    
    if (Test-Path $FilePath) {
        $result = php -l $FilePath 2>$null
        if ($LASTEXITCODE -eq 0) {
            Write-Host "  $Description`: " -NoNewline
            Write-Host "OK" -ForegroundColor Green
        } else {
            Write-Host "  $Description`: " -NoNewline
            Write-Host "Error de sintaxis" -ForegroundColor Red
        }
    }
}

Write-Host "=== VERIFICACION DE EVENTOS DEL SISTEMA DEIC ===" -ForegroundColor Green
Write-Host ""

Write-Host "1. Verificando estructura de eventos..." -ForegroundColor Yellow
Test-EventFile "app\Events\TareaCompletedStatusChanged.php" "TareaCompletedStatusChanged"
Test-EventFile "app\Events\ActividadCompletedStatusChanged.php" "ActividadCompletedStatusChanged"

Write-Host ""
Write-Host "2. Verificando listeners..." -ForegroundColor Yellow
Test-EventFile "app\Listeners\UpdateActividadStatusOnTareaChange.php" "UpdateActividadStatusOnTareaChange"
Test-EventFile "app\Listeners\UpdateProyectoStatusOnActividadChange.php" "UpdateProyectoStatusOnActividadChange"

Write-Host ""
Write-Host "3. Listando eventos registrados en Laravel..." -ForegroundColor Yellow
php artisan event:list

Write-Host ""
Write-Host "4. Verificando sintaxis de archivos..." -ForegroundColor Yellow

Write-Host "Verificando eventos:"
Test-PhpSyntax "app\Events\TareaCompletedStatusChanged.php" "TareaCompletedStatusChanged"
Test-PhpSyntax "app\Events\ActividadCompletedStatusChanged.php" "ActividadCompletedStatusChanged"

Write-Host "Verificando listeners:"
Test-PhpSyntax "app\Listeners\UpdateActividadStatusOnTareaChange.php" "UpdateActividadStatusOnTareaChange"
Test-PhpSyntax "app\Listeners\UpdateProyectoStatusOnActividadChange.php" "UpdateProyectoStatusOnActividadChange"

Write-Host ""
Write-Host "5. Verificando registro en AppServiceProvider..." -ForegroundColor Yellow

if (Test-Path "app\Providers\AppServiceProvider.php") {
    $content = Get-Content "app\Providers\AppServiceProvider.php" -Raw
    
    if ($content -match "TareaCompletedStatusChanged") {
        Write-Host "  TareaCompletedStatusChanged registrado: " -NoNewline
        Write-Host "OK" -ForegroundColor Green
    } else {
        Write-Host "  TareaCompletedStatusChanged registrado: " -NoNewline
        Write-Host "No encontrado" -ForegroundColor Red
    }
    
    if ($content -match "ActividadCompletedStatusChanged") {
        Write-Host "  ActividadCompletedStatusChanged registrado: " -NoNewline
        Write-Host "OK" -ForegroundColor Green
    } else {
        Write-Host "  ActividadCompletedStatusChanged registrado: " -NoNewline
        Write-Host "No encontrado" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "=== VERIFICACION COMPLETADA ===" -ForegroundColor Green
