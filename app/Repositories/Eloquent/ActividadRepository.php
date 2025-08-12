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
        
        // Filtro por estatus de actividad
        if (isset($filters['estatus'])) {
            switch ($filters['estatus']) {
                case 'Completo':
                    $query->whereNotNull('completed_at');
                    break;
                case 'Pendiente':
                    $query->whereNull('completed_at')
                          ->whereDoesntHave('tareas', function ($q) {
                              $q->whereNotNull('completed_at');
                          });
                    break;
                case 'Iniciado':
                    $query->whereNull('completed_at')
                          ->whereHas('tareas', function ($q) {
                              $q->whereNotNull('completed_at');
                          });
                    break;
            }
        }
    }
}
