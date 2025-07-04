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
     * Verificar si el proyecto está completado (todas las tareas completadas)
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Obtener el progreso del proyecto basado en las tareas completadas
     */
    public function getProgress(): array
    {
        $totalTareas = 0;
        $tareasCompletadas = 0;

        foreach ($this->actividades as $actividad) {
            foreach ($actividad->tareas as $tarea) {
                $totalTareas++;
                if ($tarea->completed_at) {
                    $tareasCompletadas++;
                }
            }
        }

        $porcentaje = $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100, 2) : 0;

        return [
            'total_tareas' => $totalTareas,
            'tareas_completadas' => $tareasCompletadas,
            'tareas_pendientes' => $totalTareas - $tareasCompletadas,
            'porcentaje_completado' => $porcentaje,
            'completado' => $this->isCompleted()
        ];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
