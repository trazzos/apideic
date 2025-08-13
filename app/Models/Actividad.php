<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actividad extends Model
{
    use SoftDeletes;

    protected $table = 'actividades';

    protected $guarded = [];
    protected $casts = [
        'autoridad_participante' => 'json',
        'persona_beneficiada' => 'json',
        'completed_at' => 'datetime',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }
    public function tipoActividad(): BelongsTo
    {
        return $this->belongsTo(TipoActividad::class, 'tipo_actividad_id');
    }

    public function capacitador(): BelongsTo
    {
        return $this->belongsTo(Capacitador::class, 'capacitador_id');
    }

    public function beneficiario(): BelongsTo
    {
        return $this->belongsTo(Beneficiario::class, 'beneficiario_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'responsable_id');
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Verificar si la actividad está completada (todas las tareas completadas)
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Obtener el progreso de la actividad basado en las tareas completadas
     */
    public function getProgress(): array
    {
        $totalTareas = $this->tareas()->count();
        $tareasCompletadas = $this->tareas()->whereNotNull('completed_at')->count();
        $tareasPendientes = $totalTareas - $tareasCompletadas;

        $porcentaje = $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100, 2) : 0;

        return [
            'total_tareas' => $totalTareas,
            'tareas_completadas' => $tareasCompletadas,
            'tareas_pendientes' => $tareasPendientes,
            'porcentaje_completado' => $porcentaje,
            'completado' => $this->isCompleted()
        ];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
