<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use SoftDeletes;

    protected $table = 'proyectos';

    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function tipoProyecto(): BelongsTo
    {
        return $this->belongsTo(TipoProyecto::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    
    /**
     * Verificar si el proyecto está completado (todas las actividades completadas)
     */
    public function isCompleted(): bool
    {
        $totalActividades = $this->actividades()->count();
        
        if ($totalActividades === 0) {
            return false;
        }

        $actividadesCompletadas = $this->actividades()->whereNotNull('completed_at')->count();
        
        return $actividadesCompletadas === $totalActividades;
    }

    /**
     * Obtener el progreso del proyecto basado en actividades completadas
     */
    public function getProgress(): array
    {
        $totalActividades = $this->actividades()->count();
        
        if ($totalActividades === 0) {
            return [
                'total_actividades' => 0,
                'actividades_completadas' => 0,
                'actividades_pendientes' => 0,
                'porcentaje_completado' => 0.00,
                'completado' => false
            ];
        }

        $actividadesCompletadas = $this->actividades()->whereNotNull('completed_at')->count();
        $actividadesPendientes = $totalActividades - $actividadesCompletadas;
        $porcentajeCompletado = round(($actividadesCompletadas / $totalActividades) * 100, 2);

        return [
            'total_actividades' => $totalActividades,
            'actividades_completadas' => $actividadesCompletadas,
            'actividades_pendientes' => $actividadesPendientes,
            'porcentaje_completado' => $porcentajeCompletado,
            'completado' => $actividadesCompletadas === $totalActividades
        ];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
