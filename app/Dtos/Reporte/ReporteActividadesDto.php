<?php

namespace App\Dtos\Reporte;

use App\Http\Requests\Reporte\ReporteActividadesRequest;
use Carbon\Carbon;

class ReporteActividadesDto
{
    public function __construct(
        public readonly ?Carbon $fechaInicio = null,
        public readonly ?Carbon $fechaFin = null,
        public readonly ?int $tipoProyectoId = null,
        public readonly ?string $estatus = null,
        public readonly int $perPage = 15,
        public readonly int $page = 1
    ) {}

    /**
     * Crear DTO desde Request validado.
     */
    public static function fromRequest(ReporteActividadesRequest $request): self
    {
        return new self(
            fechaInicio: $request->get('fecha_inicio') ? Carbon::parse($request->get('fecha_inicio')) : null,
            fechaFin: $request->get('fecha_fin') ? Carbon::parse($request->get('fecha_fin')) : null,
            tipoProyectoId: $request->get('tipo_proyecto_id'),
            estatus: $request->get('estatus'),
            perPage: $request->get('per_page', 15),
            page: $request->get('page', 1)
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
}
