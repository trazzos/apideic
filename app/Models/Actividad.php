<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;

class Actividad extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

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

    /**
     * Verificar si el usuario puede trabajar en esta actividad.
     * Sigue la misma lógica jerárquica que los filtros.
     */
    public function canBeWorkedByUser(?User $user): bool
    {
        if (!$user) {
            return false; // Usuario no autenticado no puede trabajar
        }

        // Superadmin puede trabajar en cualquier actividad
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Usuario sin persona asociada puede trabajar en cualquier actividad
        $persona = $user->owner;
        if (!$persona || !($persona instanceof Persona)) {
            return true;
        }

        // Verificar según el nivel jerárquico del usuario
        return $this->checkHierarchicalAccess($persona);
    }

    /**
     * Verificar acceso jerárquico basado en la persona del usuario.
     */
    private function checkHierarchicalAccess(Persona $persona): bool
    {
        // Si es el responsable directo de la actividad
        if ($this->responsable_id === $persona->id) {
            return true;
        }

        // Obtener la dependencia de la persona
        $dependencia = $persona->dependencia;
        if (!$dependencia) {
            return false;
        }

        // Verificar según el tipo de dependencia
        switch (get_class($dependencia)) {
            case 'App\Models\Secretaria':
                return $this->canBeAccessedFromSecretaria($dependencia);
            
            case 'App\Models\Subsecretaria':
                return $this->canBeAccessedFromSubsecretaria($dependencia);
            
            case 'App\Models\Direccion':
                return $this->canBeAccessedFromDireccion($dependencia);
            
            case 'App\Models\Departamento':
                return $this->canBeAccessedFromDepartamento($dependencia);
            
            default:
                return false;
        }
    }

    /**
     * Verificar si la actividad puede ser accedida desde una Secretaría.
     */
    private function canBeAccessedFromSecretaria($secretaria): bool
    {
        // Puede acceder a actividades de cualquier departamento subordinado
        $departamento = $this->proyecto->departamento;
        if (!$departamento) {
            return false;
        }

        // Verificar si el departamento está en la jerarquía de la secretaría
        $direccion = $departamento->direccion;
        if (!$direccion) {
            return false;
        }

        $subsecretaria = $direccion->subsecretaria;
        if (!$subsecretaria) {
            return false;
        }

        return $subsecretaria->secretaria_id === $secretaria->id;
    }

    /**
     * Verificar si la actividad puede ser accedida desde una Subsecretaría.
     */
    private function canBeAccessedFromSubsecretaria($subsecretaria): bool
    {
        // Puede acceder a actividades de departamentos de sus direcciones
        $departamento = $this->proyecto->departamento;
        if (!$departamento) {
            return false;
        }

        $direccion = $departamento->direccion;
        if (!$direccion) {
            return false;
        }

        return $direccion->subsecretaria_id === $subsecretaria->id;
    }

    /**
     * Verificar si la actividad puede ser accedida desde una Dirección.
     */
    private function canBeAccessedFromDireccion($direccion): bool
    {
        // Puede acceder a actividades de sus departamentos directos
        $departamento = $this->proyecto->departamento;
        if (!$departamento) {
            return false;
        }

        return $departamento->direccion_id === $direccion->id;
    }

    /**
     * Verificar si la actividad puede ser accedida desde un Departamento.
     */
    private function canBeAccessedFromDepartamento($departamento): bool
    {
        return false;
    }

    /**
     * Atributo computado para verificar si puede ser trabajado por el usuario autenticado.
     * Este atributo se puede usar en resources y vistas.
     * Requiere que se pase el usuario como contexto.
     */
    public function getCanBeWorkedByCurrentUserAttribute(): bool
    {
        // Intentar obtener el usuario autenticado
        $user = null;
        if (Auth::check()) {
            $user = Auth::user();
        }
        
        return $this->canBeWorkedByUser($user);
    }

    /**
     * Método helper para establecer si puede ser trabajado por un usuario específico.
     * Útil para usar en resources donde se tiene acceso al usuario.
     */
    public function setUserContext(?User $user): self
    {
        $this->setAttribute('_user_context', $user);
        return $this;
    }

    /**
     * Obtener si puede ser trabajado considerando el contexto del usuario.
     */
    public function getCanBeWorkedAttribute(): bool
    {
        $user = $this->getAttribute('_user_context');
        return $this->canBeWorkedByUser($user);
    }
}
