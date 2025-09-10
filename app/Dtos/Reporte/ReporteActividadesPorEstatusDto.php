<?php

namespace App\Dtos\Reporte;

use App\Http\Requests\Reporte\ReporteActividadesPorEstatusRequest;
use App\Models\User;

class ReporteActividadesPorEstatusDto extends BaseReporteDto
{
    public function __construct(
        public readonly ?string $fechaInicio = null,
        public readonly ?string $fechaFin = null,
        public readonly ?int $tipoProyectoId = null,
        public readonly ?string $estatus = null,
        public readonly ?int $departamentoId = null,
        ?User $user = null
    ) {
        parent::__construct($user);
    }

    /**
     * Crear DTO desde el request validado
     */
    public static function fromRequest(ReporteActividadesPorEstatusRequest $request, User $user): self
    {
        return new self(
            fechaInicio: $request->validated('fecha_inicio'),
            fechaFin: $request->validated('fecha_fin'),
            tipoProyectoId: $request->validated('tipo_proyecto_id'),
            estatus: $request->validated('estatus'),
            departamentoId: $request->validated('departamento_id'),
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
     * Verificar si se debe filtrar por estatus.
     */
    public function shouldFilterByDepartamento(): bool
    {
        return $this->departamentoId !== null;
    }

     /**
     * Obtener condición de estatus para la query.
     */
    public function getEstatusCondition(): ?string
    {
        return match($this->estatus) {
            'completado' => 'completed',
            'en_curso' => 'started',
            'pendiente' => 'pending',
            default => null
        };
    }


    /**
     * Obtener array de filtros aplicados
     */
    public function getFiltrosAplicados(): array
    {
        return array_filter([
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'tipo_proyecto_id' => $this->tipoProyectoId,
            'estatus' => $this->estatus,
            'departamento_id' => $this->departamentoId
        ], fn($value) => !is_null($value));
    }
}
