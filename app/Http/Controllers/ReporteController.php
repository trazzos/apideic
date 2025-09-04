<?php

namespace App\Http\Controllers;

use App\Dtos\Reporte\ReporteActividadesDto;
use App\Http\Requests\Reporte\ReporteActividadesRequest;
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
     * Buscar actividades usando el sistema de búsqueda centralizado.
     * 
     * @param Request $request
     * @return JsonResource
     */
    public function buscarActividades(Request $request): JsonResource
    {
        // Crear criterios desde el request
        $criteria = \App\Services\Search\SearchCriteria::fromRequest($request);
        
        // Añadir filtros específicos si se proporcionan
        if ($request->has('tipo_proyecto_id')) {
            $criteria->addFilter('tipo_proyecto_id', $request->get('tipo_proyecto_id'));
        }
        
        if ($request->has('estatus')) {
            $criteria->addFilter('estatus', $request->get('estatus'));
        }
        
        // Configurar relaciones para cargar
        $criteria->setRelations([
            'proyecto.tipoProyecto',
            'tipoActividad',
            'responsable'
        ]);
        
        // Usar paginación si se especifica
        if ($request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 15);
            $actividades = $this->reporteService->buscarActividadesConPaginacion($criteria, $perPage);
        } else {
            $actividades = $this->reporteService->buscarActividades($criteria);
        }

        return JsonResource::make($actividades);
    }
}
