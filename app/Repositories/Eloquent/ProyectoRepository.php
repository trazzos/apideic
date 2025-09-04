<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\ProyectoRepositoryInterface;
use App\Interfaces\Repositories\SearchableRepositoryInterface;
use App\Models\Proyecto;
use App\Repositories\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;

class ProyectoRepository extends BaseEloquentRepository implements ProyectoRepositoryInterface, SearchableRepositoryInterface
{
    use  Searchable;
    /**
     * @param Proyecto $model
     */
    public function __construct(Proyecto $model)
    {
        parent::__construct($model);
    }

    /**
     * Aplicar filtros específicos para proyectos.
     * @param Builder $query
     * @param array $filters
     */
    protected function applyCustomFilters(Builder $query, array $filters): void
    {
        // Filtro por tipo de proyecto
        if (isset($filters['tipo_proyecto_id'])) {
            $query->where('tipo_proyecto_id', $filters['tipo_proyecto_id']);
        }
        
        // Filtro por departamento específico
        if (isset($filters['departamento_id'])) {
            $query->where('departamento_id', $filters['departamento_id']);
        }

        // Filtro por múltiples departamentos (jerárquico)
        if (isset($filters['departamento_ids'])) {
            $query->whereIn('departamento_id', $filters['departamento_ids']);
        }

        // Filtro por responsable (para usuarios de departamento)
        // Los usuarios de departamento ven proyectos donde tienen actividades asignadas
        if (isset($filters['responsable_persona_id'])) {
            $query->whereHas('actividades', function ($q) use ($filters) {
                $q->where('responsable_id', $filters['responsable_persona_id']);
            })->distinct();
        }
        
        // Filtro por estatus del proyecto
        if (isset($filters['estatus'])) {
            switch ($filters['estatus']) {
                case 'Completado':
                    $query->whereNotNull('completed_at');
                    break;
                case 'Pendiente':
                    $query->whereNull('completed_at');
                    break;
            }
        }
    }
}
