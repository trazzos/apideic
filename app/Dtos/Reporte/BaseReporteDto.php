<?php

namespace App\Dtos\Reporte;

use App\Models\User;
use App\Models\Persona;

abstract class BaseReporteDto
{
    protected ?User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Verificar si el usuario puede ver todas las actividades.
     */
    public function canViewAllActivities(): bool
    {
        if (!$this->user) {
            return false; // Usuario no autenticado no puede ver
        }

        // Superadmin puede ver todas las actividades
        if ($this->user->hasRole('superadmin')) {
            return true;
        }

        // Usuario sin persona asociada puede ver todo
        $persona = $this->user->owner;
        if (!$persona || !($persona instanceof Persona)) {
            return true;
        }

        return false;
    }

    /**
     * Obtener la persona asociada al usuario.
     */
    public function getPersona(): ?Persona
    {
        if (!$this->user) {
            return null;
        }

        $owner = $this->user->owner;
        
        if ($owner instanceof Persona) {
            return $owner;
        }

        return null;
    }

    /**
     * Obtener IDs de personas subordinadas según la jerarquía.
     */
    public function getSubordinatePersonaIds(): array
    {
        if ($this->canViewAllActivities()) {
            return []; // Si puede ver todo, no necesita filtrar
        }

        $persona = $this->getPersona();
        if (!$persona) {
            return [];
        }

        $subordinateIds = [$persona->id]; // Incluir al usuario actual

        // Obtener dependencia de la persona
        $dependencia = $persona->dependencia;
        if (!$dependencia) {
            return $subordinateIds;
        }

        switch (get_class($dependencia)) {
            case 'App\Models\Secretaria':
                $subordinateIds = array_merge($subordinateIds, $this->getPersonasFromSecretaria($dependencia));
                break;
            case 'App\Models\Subsecretaria':
                $subordinateIds = array_merge($subordinateIds, $this->getPersonasFromSubsecretaria($dependencia));
                break;
            case 'App\Models\Direccion':
                $subordinateIds = array_merge($subordinateIds, $this->getPersonasFromDireccion($dependencia));
                break;
            case 'App\Models\Departamento':
                $subordinateIds = array_merge($subordinateIds, $this->getPersonasFromDepartamento($dependencia, $persona));
                break;
        }

        return array_unique($subordinateIds);
    }

    /**
     * Obtener personas subordinadas a una Secretaría.
     */
    private function getPersonasFromSecretaria($secretaria): array
    {
        $personaIds = [];

        // Personas directamente asignadas a la secretaría
        $personaIds = array_merge($personaIds, 
            $secretaria->personas()->pluck('id')->toArray()
        );

        // Personas de subsecretarías
        foreach ($secretaria->subsecretarias as $subsecretaria) {
            $personaIds = array_merge($personaIds, $this->getPersonasFromSubsecretaria($subsecretaria));
        }

        return $personaIds;
    }

    /**
     * Obtener personas subordinadas a una Subsecretaría.
     */
    private function getPersonasFromSubsecretaria($subsecretaria): array
    {
        $personaIds = [];

        // Personas directamente asignadas a la subsecretaría
        $personaIds = array_merge($personaIds, 
            $subsecretaria->personas()->pluck('id')->toArray()
        );

        // Personas de direcciones
        foreach ($subsecretaria->direcciones as $direccion) {
            $personaIds = array_merge($personaIds, $this->getPersonasFromDireccion($direccion));
        }

        return $personaIds;
    }

    /**
     * Obtener personas subordinadas a una Dirección.
     */
    private function getPersonasFromDireccion($direccion): array
    {
        $personaIds = [];

        // Personas directamente asignadas a la dirección
        $personaIds = array_merge($personaIds, 
            $direccion->personas()->pluck('id')->toArray()
        );

        // Personas de departamentos
        foreach ($direccion->departamentos as $departamento) {
            $personaIds = array_merge($personaIds, 
                $departamento->personas()->pluck('id')->toArray()
            );
        }

        return $personaIds;
    }

    /**
     * Obtener personas subordinadas a un Departamento.
     * Incluye lógica específica para titular vs no titular.
     */
    private function getPersonasFromDepartamento($departamento, $persona): array
    {
        $personaIds = [];

        // Si la persona es titular del departamento, puede ver todas las actividades
        // de los proyectos que pertenecen a su departamento
        if ($persona->es_titular === 'Si') {
            // Obtener todas las personas que son responsables de actividades 
            // en proyectos de este departamento
            $personaIds = \App\Models\Actividad::whereHas('proyecto', function($query) use ($departamento) {
                $query->where('departamento_id', $departamento->id);
            })->distinct('responsable_id')->pluck('responsable_id')->toArray();
        }
        // Si no es titular, solo puede ver sus propias actividades donde es responsable
        // (esto ya está incluido con $persona->id en el array inicial)
        
        return array_filter($personaIds); // Filtrar nulls
    }

    /**
     * Verificar si la persona puede ver actividades del departamento completo.
     */
    public function canViewDepartmentActivities(): bool
    {
        $persona = $this->getPersona();
        if (!$persona) {
            return false;
        }

        $dependencia = $persona->dependencia;
        
        // Si pertenece a un departamento y es titular
        if ($dependencia instanceof \App\Models\Departamento && $persona->es_titular === 'Si') {
            return true;
        }

        // Si pertenece a niveles superiores (Dirección, Subsecretaría, Secretaría)
        if ($dependencia instanceof \App\Models\Direccion || 
            $dependencia instanceof \App\Models\Subsecretaria || 
            $dependencia instanceof \App\Models\Secretaria) {
            return true;
        }

        return false;
    }

    /**
     * Obtener IDs de departamentos accesibles según la jerarquía.
     */
    public function getAccessibleDepartmentIds(): array
    {
        if ($this->canViewAllActivities()) {
            return []; // Si puede ver todo, no necesita filtrar
        }

        $persona = $this->getPersona();
        if (!$persona) {
            return [];
        }

        $dependencia = $persona->dependencia;
        if (!$dependencia) {
            return [];
        }

        $departmentIds = [];

        switch (get_class($dependencia)) {
            case 'App\Models\Secretaria':
                // Todos los departamentos de la secretaría
                $departmentIds = \App\Models\Departamento::whereHas('direccion.subsecretaria', function($query) use ($dependencia) {
                    $query->where('secretaria_id', $dependencia->id);
                })->pluck('id')->toArray();
                break;
                
            case 'App\Models\Subsecretaria':
                // Todos los departamentos de la subsecretaría
                $departmentIds = \App\Models\Departamento::whereHas('direccion', function($query) use ($dependencia) {
                    $query->where('subsecretaria_id', $dependencia->id);
                })->pluck('id')->toArray();
                break;
                
            case 'App\Models\Direccion':
                // Todos los departamentos de la dirección
                $departmentIds = $dependencia->departamentos()->pluck('id')->toArray();
                break;
                
            case 'App\Models\Departamento':
                // Solo su propio departamento si es titular, ninguno si no es titular
                if ($persona->es_titular === 'Si') {
                    $departmentIds = [$dependencia->id];
                }
                break;
        }

        return $departmentIds;
    }
}
