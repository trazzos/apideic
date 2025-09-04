<?php

namespace App\Dtos\Reporte;

use App\Http\Requests\Reporte\ReporteActividadesRequest;
use App\Models\User;
use Carbon\Carbon;

class ReporteActividadesDto
{
    public function __construct(
        public readonly int $perPage = 15,
        public readonly int $page = 1,
        public readonly ?Carbon $fechaInicio = null,
        public readonly ?Carbon $fechaFin = null,
        public readonly ?int $tipoProyectoId = null,
        public readonly ?string $estatus = null,
        public readonly ?User $user = null
    ) {}

    /**
     * Crear DTO desde Request validado.
     */
    public static function fromRequest(ReporteActividadesRequest $request, ?User $user = null): self
    {
        return new self(
            perPage: $request->get('per_page', 15),
            page: $request->get('page', 1),
            fechaInicio: $request->get('fecha_inicio') ? Carbon::parse($request->get('fecha_inicio')) : null,
            fechaFin: $request->get('fecha_fin') ? Carbon::parse($request->get('fecha_fin')) : null,
            tipoProyectoId: $request->get('tipo_proyecto_id'),
            estatus: $request->get('estatus'),
            user: $user
        );
    }

    /**
     * Verificar si se debe filtrar por fecha.
     */
    public function shouldFilterByDate(): bool
    {
        return $this->fechaInicio !== null || $this->fechaFin !== null;
    }

    /**
     * Verificar si se debe filtrar por tipo de proyecto.
     */
    public function shouldFilterByTipoProyecto(): bool
    {
        return $this->tipoProyectoId !== null;
    }

    /**
     * Verificar si se debe filtrar por estatus.
     */
    public function shouldFilterByEstatus(): bool
    {
        return $this->estatus !== null;
    }

    /**
     * Obtener condición de estatus para la query.
     */
    public function getEstatusCondition(): ?string
    {
        return match($this->estatus) {
            'completo' => 'completed',
            'pendiente' => 'pending',
            'iniciado' => 'started',
            default => null
        };
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
                // Solo sus propias actividades
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
}
