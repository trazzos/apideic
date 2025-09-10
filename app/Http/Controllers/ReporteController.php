<?php

namespace App\Http\Controllers;

use App\Dtos\Reporte\ReporteActividadesDto;
use App\Dtos\Reporte\ReporteActividadesPorEstatusDto;
use App\Http\Requests\Reporte\ReporteActividadesRequest;
use App\Http\Requests\Reporte\ReporteActividadesPorEstatusRequest;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;

class ReporteController extends BaseController
{

    /**
     * @param ReporteService $reporteService
     */
    public function __construct(
        private readonly ReporteService $reporteService
    )
    {
        //$this->middleware('permission:reporte')->only(['reporteActividades']);
        //$this->middleware('permission:reporte.crear')->only(['store']);
        //$this->middleware('permission:reporte.editar')->only(['update']);
    }

    /**
     * Generar reporte de actividades agrupadas por tipo de proyecto.
     * 
     * @param ReporteActividadesRequest $request
     * @return JsonResource
     */
    public function actividadesPorTipoProyecto(ReporteActividadesRequest $request): JsonResource
    {
        $dto = ReporteActividadesDto::fromRequest($request, $request->user());
        $reporte = $this->reporteService->generarReporteActividades($dto);

        return JsonResource::make($reporte);
    }

    /**
     * Buscar actividades por estatus usando el sistema de búsqueda centralizado.
     * 
     * @param ReporteActividadesPorEstatusRequest $request
     * @return JsonResource
     */
    public function buscarActividades(ReporteActividadesPorEstatusRequest $request): JsonResource
    {
        $dto = ReporteActividadesPorEstatusDto::fromRequest($request, $request->user());
        $reporte = $this->reporteService->generarReporteActividadesPorEstatus($dto);
        
        return JsonResource::make($reporte);
    }
}
