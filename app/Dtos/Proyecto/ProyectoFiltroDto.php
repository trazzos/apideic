<?php

namespace App\Dtos\Proyecto;

use App\Models\User;
use Carbon\Carbon;

class ProyectoFiltroDto
{
    public function __construct(
        public readonly ?int $tipoProyectoId = null,
        public readonly ?int $departamentoId = null,
        public readonly ?string $estatus = null, // 'pendiente' o 'completado'
        public readonly int $perPage = 15,
        public readonly int $page = 1,
        public readonly ?User $user = null
    ) {}

    /**
     * Crear DTO desde Request.
     */
    public static function fromRequest($request, ?User $user = null): self
    {
        return new self(
            tipoProyectoId: $request->get('tipo_proyecto_id'),
            departamentoId: $request->get('departamento_id'),
            estatus: $request->get('estatus'),
            perPage: $request->get('per_page', 15),
            page: $request->get('page', 1),
            user: $user
        );
    }

    /**
     * Verificar si se debe filtrar por tipo de proyecto.
     */
    public function shouldFilterByTipoProyecto(): bool
    {
        return $this->tipoProyectoId !== null;
    }

    /**
     * Verificar si se debe filtrar por departamento.
     */
    public function shouldFilterByDepartamento(): bool
    {
        return $this->departamentoId !== null;
    }

    /**
     * Verificar si se debe filtrar por estatus.
     */
    public function shouldFilterByEstatus(): bool
    {
        return $this->estatus !== null;
    }

    /**
     * Verificar si el usuario puede ver todos los proyectos.
     */
    public function canViewAllProjects(): bool
    {
        if (!$this->user) {
            return false; // Usuario no autenticado no puede ver
        }

        // Superadmin puede ver todos los proyectos
        if ($this->user->hasRole('superadmin')) {
            return true;
        }

        // Usuario sin persona asociada puede ver todo
        $persona = $this->user->owner;
        if (!$persona || !($persona instanceof \App\Models\Persona)) {
            return true;
        }

        return false;
    }

    /**
     * Obtener la persona asociada al usuario.
     */
    public function getPersona(): ?\App\Models\Persona
    {
        if (!$this->user) {
            return null;
        }

        $owner = $this->user->owner;
        
        if ($owner instanceof \App\Models\Persona) {
            return $owner;
        }

        return null;
    }

    /**
     * Obtener IDs de departamentos que el usuario puede ver según la jerarquía.
     */
    public function getAllowedDepartamentoIds(): array
    {
        if ($this->canViewAllProjects()) {
            return []; // Si puede ver todo, no necesita filtrar
        }

        $persona = $this->getPersona();
        if (!$persona) {
            return [];
        }

        $allowedIds = [];

        // Obtener dependencia de la persona
        $dependencia = $persona->dependencia;
        if (!$dependencia) {
            return $allowedIds;
        }

        switch (get_class($dependencia)) {
            case 'App\Models\Secretaria':
                $allowedIds = $this->getDepartamentosFromSecretaria($dependencia);
                break;
            case 'App\Models\Subsecretaria':
                $allowedIds = $this->getDepartamentosFromSubsecretaria($dependencia);
                break;
            case 'App\Models\Direccion':
                $allowedIds = $this->getDepartamentosFromDireccion($dependencia);
                break;
            case 'App\Models\Departamento':
                 // Solo puede ver proyectos donde es responsable (no por departamento)
                $allowedIds = [];
                break;
        }

        return array_unique($allowedIds);
    }

    /**
     * Verificar si el usuario pertenece a un departamento (caso especial).
     */
    public function isUserFromDepartamento(): bool
    {
        if ($this->canViewAllProjects()) {
            return false;
        }

        $persona = $this->getPersona();
        if (!$persona) {
            return false;
        }

        $dependencia = $persona->dependencia;
        return $dependencia && get_class($dependencia) === 'App\Models\Departamento';
    }

    /**
     * Obtener el ID del departamento del usuario (si pertenece a uno).
     */
    public function getUserDepartamentoId(): ?int
    {
        if (!$this->isUserFromDepartamento()) {
            return null;
        }

        $persona = $this->getPersona();
        return $persona->dependencia->id ?? null;
    }

    /**
     * Obtener departamentos subordinados a una Secretaría.
     */
    private function getDepartamentosFromSecretaria($secretaria): array
    {
        $departamentoIds = [];

        // Departamentos de subsecretarías
        foreach ($secretaria->subsecretarias as $subsecretaria) {
            $departamentoIds = array_merge($departamentoIds, $this->getDepartamentosFromSubsecretaria($subsecretaria));
        }

        return $departamentoIds;
    }

    /**
     * Obtener departamentos subordinados a una Subsecretaría.
     */
    private function getDepartamentosFromSubsecretaria($subsecretaria): array
    {
        $departamentoIds = [];

        // Departamentos de direcciones
        foreach ($subsecretaria->direcciones as $direccion) {
            $departamentoIds = array_merge($departamentoIds, $this->getDepartamentosFromDireccion($direccion));
        }

        return $departamentoIds;
    }

    /**
     * Obtener departamentos subordinados a una Dirección.
     */
    private function getDepartamentosFromDireccion($direccion): array
    {
        return $direccion->departamentos()->pluck('id')->toArray();
    }

    /**
     * Verificar si el usuario tiene acceso a un departamento específico.
     */
    public function hasAccessToDepartamento(int $departamentoId): bool
    {
        if ($this->canViewAllProjects()) {
            return true; // Puede ver todos los departamentos
        }

        // Si es usuario de departamento, solo puede acceder a proyectos donde es responsable
        // No por departamento específico
        if ($this->isUserFromDepartamento()) {
            return false; // Los usuarios de departamento no filtran por departamento_id
        }

        // Para otros niveles jerárquicos, verificar si el departamento está en su lista permitida
        $allowedDepartamentoIds = $this->getAllowedDepartamentoIds();
        return in_array($departamentoId, $allowedDepartamentoIds);
    }

    /**
     * Obtener el filtro final de departamentos considerando prioridad del request.
     * Si se proporciona departamento_id en request y el usuario tiene acceso, se usa ese.
     * Sino, se usan los departamentos jerárquicos.
     */
    public function getFinalDepartamentoFilter(): array
    {
        // Si puede ver todos los proyectos, no aplicar filtro
        if ($this->canViewAllProjects() && !$this->shouldFilterByDepartamento()) {
            return [];
        }

        // Si se proporciona departamento_id en request
        if ($this->shouldFilterByDepartamento()) {
            // Verificar si tiene acceso a ese departamento específico
            if ($this->hasAccessToDepartamento($this->departamentoId)) {
                return ['departamento_id' => $this->departamentoId];
            } else {
                // No tiene acceso al departamento solicitado, no mostrar nada
                return ['departamento_ids' => [0]]; // ID inexistente
            }
        }

        // Si es usuario de departamento, usar filtro por responsable ,pero si es titular de ese departamento puede ver todo el departamento
        if ($this->isUserFromDepartamento()) {
            $persona = $this->getPersona();
            if ($persona) {
                if ($persona->es_titular === 'Si') {
                    return ['departamento_id' => $persona->dependencia->id];
                }
                return ['responsable_persona_id' => $persona->id];
            }
            return ['departamento_ids' => [0]]; // Sin acceso
        }

        // Usar filtro jerárquico normal
        $allowedDepartamentoIds = $this->getAllowedDepartamentoIds();
        if (!empty($allowedDepartamentoIds)) {
            return ['departamento_ids' => $allowedDepartamentoIds];
        }

        return ['departamento_ids' => [0]]; // Sin acceso
    }
}
