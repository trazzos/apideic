<?php

namespace App\Dtos\Reporte;

use App\Http\Requests\Reporte\ReporteActividadesRequest;
use App\Models\User;
use Carbon\Carbon;

class ReporteActividadesDto extends BaseReporteDto
{
    public function __construct(
        public readonly int $perPage = 15,
        public readonly int $page = 1,
        public readonly ?Carbon $fechaInicio = null,
        public readonly ?Carbon $fechaFin = null,
        public readonly ?int $tipoProyectoId = null,
        public readonly ?string $estatus = null,
        public readonly bool $agruparPorEstatus = false,
        ?User $user = null
    ) {
        parent::__construct($user);
    }

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
            'completado' => 'completed',
            'en_curso' => 'started',
            'pendiente' => 'pending',
            default => null
        };
    }
}
