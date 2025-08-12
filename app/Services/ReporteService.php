<?php

namespace App\Services;

use App\Interfaces\Repositories\ActividadRepositoryInterface;
use App\Services\Traits\Searchable;
use App\Dtos\Reporte\ReporteActividadesDto;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Search\SearchCriteria;
use Illuminate\Pagination\LengthAwarePaginator;

class ReporteService extends BaseService {

    use Searchable;
    /**
     * @var ActividadRepositoryInterface
     */

    public function __construct(ActividadRepositoryInterface $actividadRepository)
    {
        $this->repository = $actividadRepository;
    }

    /**
     * Generar reporte de actividades agrupadas por tipo de proyecto.
     * @param ReporteActividadesDto $dto
     * @return array
     */
    public function generarReporteActividades(ReporteActividadesDto $dto): array
    {
        // Usar el repositorio con sistema de búsqueda centralizado
        $actividades = $this->repository->getActividadesParaReporte($dto);
        
        return $this->procesarActividadesParaReporte($actividades, $dto);
    }

    /**
     * Buscar actividades usando criterios personalizados.
     * @param SearchCriteria $criteria
     * @return Collection
     */
    public function buscarActividades(SearchCriteria $criteria): Collection
    {
        return $this->repository->search($criteria);
    }

    /**
     * Buscar actividades con paginación usando criterios personalizados.   
     * @param SearchCriteria $criteria
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function buscarActividadesConPaginacion(SearchCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->searchWithPagination($criteria, $perPage);
    }

    /**
     * Procesar actividades para generar estructura del reporte.
     * @param Collection $actividades
     * @param ReporteActividadesDto $dto
     * @return array Estructura del reporte con estadísticas y detalles de actividades
     */
    private function procesarActividadesParaReporte(Collection $actividades, ReporteActividadesDto $dto): array
    {
        
        // Agrupar por tipo de proyecto
        $reportePorTipo = $actividades->groupBy(function ($actividad) {
            return $actividad->proyecto->tipoProyecto->id;
        });

        $reporte = [];
        $totales = [
            'total_actividades' => 0,
            'actividades_completadas' => 0,
            'actividades_pendientes' => 0,
            'actividades_iniciadas' => 0
        ];

        foreach ($reportePorTipo as $tipoProyectoId => $actividadesPorTipo) {
            $primerActividad = $actividadesPorTipo->first();
            $tipoProyecto = $primerActividad->proyecto->tipoProyecto;

            // Calcular estadísticas por tipo de proyecto
            $completadas = $actividadesPorTipo->filter(fn($a) => $a->isCompleted())->count();
            $pendientes = $actividadesPorTipo->filter(function($a) {
                return !$a->isCompleted() && $a->tareas()->whereNotNull('completed_at')->count() === 0;
            })->count();
            $iniciadas = $actividadesPorTipo->filter(function($a) {
                return !$a->isCompleted() && $a->tareas()->whereNotNull('completed_at')->count() > 0;
            })->count();

            $total = $actividadesPorTipo->count();

            // Agregar a totales generales
            $totales['total_actividades'] += $total;
            $totales['actividades_completadas'] += $completadas;
            $totales['actividades_pendientes'] += $pendientes;
            $totales['actividades_iniciadas'] += $iniciadas;

            // Preparar datos detallados de actividades
            $actividadesDetalle = $actividadesPorTipo->map(function ($actividad) {
                $progress = $actividad->getProgress();
                return [
                    'id' => $actividad->id,
                    'nombre' => $actividad->nombre,
                    'descripcion' => $actividad->descripcion,
                    'fecha_inicio' => $actividad->fecha_inicio,
                    'fecha_fin' => $actividad->fecha_fin,
                    'completado' => $actividad->isCompleted(),
                    'completed_at' => $actividad->completed_at,
                    'progreso' => $progress,
                    'proyecto' => [
                        'id' => $actividad->proyecto->id,
                        'nombre' => $actividad->proyecto->nombre
                    ],
                    'tipo_actividad' => [
                        'id' => $actividad->tipoActividad->id,
                        'nombre' => $actividad->tipoActividad->nombre
                    ],
                    'responsable' => $actividad->responsable ? [
                        'id' => $actividad->responsable->id,
                        'nombre' => $actividad->responsable->nombre . ' ' . $actividad->responsable->apellido_paterno
                    ] : null
                ];
            });

            $reporte[] = [
                'tipo_proyecto' => [
                    'id' => $tipoProyecto->id,
                    'nombre' => $tipoProyecto->nombre,
                    'descripcion' => $tipoProyecto->descripcion ?? ''
                ],
                'estadisticas' => [
                    'total_actividades' => $total,
                    'actividades_completadas' => $completadas,
                    'actividades_pendientes' => $pendientes,
                    'actividades_iniciadas' => $iniciadas,
                    'porcentaje_completado' => $total > 0 ? round(($completadas / $total) * 100, 2) : 0,
                    'porcentaje_pendiente' => $total > 0 ? round(($pendientes / $total) * 100, 2) : 0,
                    'porcentaje_iniciado' => $total > 0 ? round(($iniciadas / $total) * 100, 2) : 0
                ],
                'actividades' => $actividadesDetalle
            ];
        }

        return [
            'filtros_aplicados' => [
                'fecha_inicio' => $dto->fechaInicio?->format('Y-m-d'),
                'fecha_fin' => $dto->fechaFin?->format('Y-m-d'),
                'tipo_proyecto_id' => $dto->tipoProyectoId,
                'estatus' => $dto->estatus
            ],
            'totales_generales' => [
                ...$totales,
                'porcentaje_completado' => $totales['total_actividades'] > 0 ? 
                    round(($totales['actividades_completadas'] / $totales['total_actividades']) * 100, 2) : 0,
                'porcentaje_pendiente' => $totales['total_actividades'] > 0 ? 
                    round(($totales['actividades_pendientes'] / $totales['total_actividades']) * 100, 2) : 0,
                'porcentaje_iniciado' => $totales['total_actividades'] > 0 ? 
                    round(($totales['actividades_iniciadas'] / $totales['total_actividades']) * 100, 2) : 0
            ],
            'reporte_por_tipo_proyecto' => $reporte,
            'total_tipos_proyecto' => count($reporte)
        ];
    }
}
