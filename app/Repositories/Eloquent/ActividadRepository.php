<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\ActividadRepositoryInterface;
use App\Models\Actividad;
use App\Repositories\Traits\Searchable;
use Illuminate\Database\Eloquent\Collection;
use App\Dtos\Reporte\ReporteActividadesDto;
use App\Services\Search\SearchCriteria;
use Illuminate\Database\Eloquent\Builder;

class ActividadRepository extends BaseEloquentRepository implements ActividadRepositoryInterface
{

    use Searchable;

    /**
     * ActividadRepository constructor.
     */
    public function __construct(Actividad $model)
    {
        parent::__construct($model);
        // Configurar campos de búsqueda para el trait Searchable
        $this->setSearchFields([
            'nombre',
            'descripcion',
            'proyecto.nombre',
            'tipoActividad.nombre',
            'responsable.nombre',
            'responsable.apellido_paterno'
        ]);
    }

    /**
     * Buscar actividades por el UUID del proyecto asociado.
     * @param string $uuid
     * @return Collection
     */
    public function findByProyectoUuid(string $uuid): Collection
    {
        return $this->model->whereHas('proyecto', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->get();
    }

    /**
     * Obtener actividades para reporte agrupadas por tipo de proyecto.
     * Utiliza el sistema de búsqueda centralizado con SearchCriteria.
     * @param ReporteActividadesDto $dto
     * @return Collection
     */
    public function getActividadesParaReporte(ReporteActividadesDto $dto): Collection
    {
        // Crear criterios de búsqueda usando el sistema centralizado
        $criteria = $this->buildSearchCriteriaFromDto($dto);
        
        // Usar el método search del trait Searchable
        return $this->search($criteria);
    }

    /**
     * Obtener actividades para reporte agrupadas por estatus.
     * @param \App\Dtos\Reporte\ReporteActividadesPorEstatusDto $dto
     * @return Collection
     */
    public function getActividadesParaReportePorEstatus(\App\Dtos\Reporte\ReporteActividadesPorEstatusDto $dto): Collection
    {
        // Crear criterios de búsqueda usando el sistema centralizado
        $criteria = $this->buildSearchCriteriaFromEstatusDto($dto);
        
        // Usar el método search del trait Searchable
        return $this->search($criteria);
    }

    /**
     * Construir SearchCriteria desde el DTO del reporte.
     * @param ReporteActividadesDto $dto
     * @return SearchCriteria
     */
    private function buildSearchCriteriaFromDto(ReporteActividadesDto $dto): SearchCriteria
    {
        $criteria = new \App\Services\Search\SearchCriteria();
        
        // Configurar relaciones necesarias para el reporte
        $criteria->setRelations([
            'proyecto.tipoProyecto',
            'tipoActividad',
            'responsable',
            'tareas'
        ]);
        
        // Configurar ordenamiento
        $criteria->setSort('created_at', 'desc');
        
        // Aplicar filtros específicos del reporte
        
        // Filtro por rango de fechas
        if ($dto->shouldFilterByDate()) {
            if ($dto->fechaInicio) {
                $criteria->addFilter('fecha_inicio_from', $dto->fechaInicio->format('Y-m-d'));
            }
            if ($dto->fechaFin) {
                $criteria->addFilter('fecha_fin_to', $dto->fechaFin->format('Y-m-d'));
            }
        }
        
        // Filtro por tipo de proyecto
        if ($dto->shouldFilterByTipoProyecto()) {
            $criteria->addFilter('tipo_proyecto_id', $dto->tipoProyectoId);
        }
        
        // Filtro por estatus
        if ($dto->shouldFilterByEstatus()) {
            $criteria->addFilter('estatus', $dto->estatus);
        }

        // Filtro jerárquico por responsables
        if (!$dto->canViewAllActivities()) {
            $subordinatePersonaIds = $dto->getSubordinatePersonaIds();
            if (!empty($subordinatePersonaIds)) {
                $criteria->addFilter('responsable_persona_ids', $subordinatePersonaIds);
            } else {
                // Si no tiene subordinados, no debería ver ninguna actividad
                $criteria->addFilter('responsable_persona_ids', [0]); // ID inexistente
            }
        }
        
        return $criteria;
    }

    /**
     * Construir SearchCriteria desde el DTO del reporte por estatus.
     * @param \App\Dtos\Reporte\ReporteActividadesPorEstatusDto $dto
     * @return SearchCriteria
     */
    private function buildSearchCriteriaFromEstatusDto(\App\Dtos\Reporte\ReporteActividadesPorEstatusDto $dto): SearchCriteria
    {
        $criteria = new \App\Services\Search\SearchCriteria();
        
        // Configurar relaciones necesarias para el reporte
        $criteria->setRelations([
            'proyecto.tipoProyecto',
            'proyecto.departamento',
            'tipoActividad',
            'responsable',
            'tareas'
        ]);
        
        // Configurar ordenamiento
        $criteria->setSort('created_at', 'desc');
        
        // Aplicar filtros específicos del reporte
        
        // Filtro por rango de fechas
        if ($dto->shouldFilterByDate()) {
            if ($dto->fechaInicio) {
                $criteria->addFilter('fecha_inicio_from', $dto->fechaInicio);
            }
            if ($dto->fechaFin) {
                $criteria->addFilter('fecha_fin_to', $dto->fechaFin);
            }
        }
        
        // Filtro por tipo de proyecto
        if ($dto->shouldFilterByTipoProyecto()) {
            $criteria->addFilter('tipo_proyecto_id', $dto->tipoProyectoId);
        }
        
        // Filtro por estatus
        if ($dto->shouldFilterByEstatus()) {
            $criteria->addFilter('estatus', $dto->estatus);
        }
        
        // Aplicar filtros jerárquicos según los permisos del usuario
        if (!$dto->canViewAllActivities()) {
            // Verificar si debe ver solo sus propias actividades (usuario no titular de departamento)
            $subordinatePersonaIds = $dto->getSubordinatePersonaIds();
            
            if (empty($subordinatePersonaIds)) {
                // Usuario no titular de departamento: solo ve sus propias actividades donde es responsable
                $persona = $dto->getPersona();
                if ($persona) {
                    $criteria->addFilter('responsable_persona_ids', [$persona->id]);
                } else {
                    // Sin persona asociada, no debería ver ninguna actividad
                    $criteria->addFilter('responsable_persona_ids', [0]); // ID inexistente
                }
            } else {
                // Usuario con subordinados: aplicar filtros jerárquicos por departamentos
                $accessibleDepartmentIds = $dto->getAccessibleDepartmentIds();
                
                if (!empty($accessibleDepartmentIds)) {
                    // Si hay filtro específico de departamento, verificar que esté en los accesibles
                    if ($dto->shouldFilterByDepartamento()) {
                        if (in_array($dto->departamentoId, $accessibleDepartmentIds)) {
                            // El departamento solicitado está en los accesibles, usar solo ese
                            $criteria->addFilter('departamentos_jerarquicos', [$dto->departamentoId]);
                        } else {
                            // El departamento solicitado no está en los accesibles, no debería ver nada
                            $criteria->addFilter('departamentos_jerarquicos', [0]); // ID inexistente
                        }
                    } else {
                        // No hay filtro específico, aplicar todos los departamentos accesibles
                        $criteria->addFilter('departamentos_jerarquicos', $accessibleDepartmentIds);
                    }
                } else {
                    // No tiene acceso a ningún departamento
                    $criteria->addFilter('departamentos_jerarquicos', [0]); // ID inexistente
                }
            }
        }
        
        return $criteria;
    }

    /**
     * Aplicar filtros específicos del reporte que no están en el sistema base.
     * Este método sobrescribe la aplicación de filtros para casos específicos.
     * @param Builder $query
     * @param array $filters
     * 
     */
    protected function applyCustomFilters(Builder $query, array $filters): void
    {
        // Filtro por fecha de inicio desde
        if (isset($filters['fecha_inicio_from'])) {
            $query->where('fecha_inicio', '>=', $filters['fecha_inicio_from']);
        }
        
        // Filtro por fecha de fin hasta
        if (isset($filters['fecha_fin_to'])) {
            $query->where('fecha_final', '<=', $filters['fecha_fin_to']);
        }
        
        // Filtro por tipo de proyecto
        if (isset($filters['tipo_proyecto_id'])) {
            $query->whereHas('proyecto', function ($q) use ($filters) {
                $q->where('tipo_proyecto_id', $filters['tipo_proyecto_id']);
            });
        }

        // Filtro jerárquico por responsables (para ambos tipos de reporte)
        if (isset($filters['responsable_persona_ids'])) {
            $query->whereIn('responsable_id', $filters['responsable_persona_ids']);
        }
        
        // Filtro por estatus de actividad
        if (isset($filters['estatus'])) {
            switch ($filters['estatus']) {
                case 'completado':
                    $query->whereNotNull('completed_at');
                    break;
                case 'en_curso':
                    $query->whereNull('completed_at')
                          ->whereHas('tareas', function ($q) {
                              $q->whereNotNull('completed_at');
                          });
                    break;
                case 'sin_iniciar':
                    $query->whereNull('completed_at')
                          ->whereDoesntHave('tareas', function ($q) {
                              $q->whereNotNull('completed_at');
                          });
                    break;
            }
        }

        // Filtros jerárquicos por departamentos
        if (isset($filters['departamentos_jerarquicos'])) {
            // Filtro jerárquico por departamentos (individual o múltiples según permisos del usuario)
            $query->whereHas('proyecto', function ($q) use ($filters) {
                $q->whereIn('departamento_id', $filters['departamentos_jerarquicos']);
            });
        }

       
    }
}
