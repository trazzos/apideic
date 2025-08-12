<?php

namespace App\Repositories\Traits;

use App\Services\Search\SearchCriteria;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait para implementar funcionalidades de búsqueda en repositorios.
 * 
 * Proporciona métodos estandarizados para aplicar criterios de búsqueda,
 * filtros y ordenamiento de manera consistente.
 */
trait Searchable
{
    /**
     * Campos por defecto para búsqueda de texto.
     */
    protected array $defaultSearchFields = ['nombre'];

    /**
     * Buscar registros usando criterios específicos.
     */
    public function search(SearchCriteria $criteria): Collection
    {
        $query = $this->getSearchQuery($criteria);
        return $query->get();
    }

    /**
     * Buscar registros con paginación usando criterios específicos.
     */
    public function searchWithPagination(
        SearchCriteria $criteria, 
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = $this->getSearchQuery($criteria);
        return $query->paginate($perPage);
    }

    /**
     * Obtener query con criterios aplicados.
     */
    protected function getSearchQuery(SearchCriteria $criteria): Builder
    {
        $query = $this->model->newQuery();
        
        // Establecer campos de búsqueda si no están definidos
        if (empty($criteria->getSearchFields())) {
            $criteria->setSearchFields($this->getSearchFields());
        }
        
        // Aplicar criterios usando método personalizable
        return $this->applyCriteria($query, $criteria);
    }

    /**
     * Aplicar criterios de búsqueda al query.
     * Este método puede ser sobrescrito en repositorios específicos.
     */
    protected function applyCriteria(Builder $query, SearchCriteria $criteria): Builder
    {
        // Aplicar relaciones
        if (!empty($criteria->getRelations())) {
            $query->with($criteria->getRelations());
        }
        
        // Aplicar filtros personalizados si el método existe
        if (method_exists($this, 'applyCustomFilters')) {
            $this->applyCustomFilters($query, $criteria->getFilters());
            
            // Aplicar búsqueda de texto manualmente
            if (!empty($criteria->getSearchTerm()) && !empty($criteria->getSearchFields())) {
                $query->where(function ($q) use ($criteria) {
                    foreach ($criteria->getSearchFields() as $field) {
                        if (str_contains($field, '.')) {
                            // Campo de relación
                            [$relation, $relationField] = explode('.', $field, 2);
                            $q->orWhereHas($relation, function ($relationQuery) use ($relationField, $criteria) {
                                $relationQuery->where($relationField, 'LIKE', "%{$criteria->getSearchTerm()}%");
                            });
                        } else {
                            // Campo directo
                            $q->orWhere($field, 'LIKE', "%{$criteria->getSearchTerm()}%");
                        }
                    }
                });
            }
            
            // Aplicar ordenamiento manualmente
            if (str_contains($criteria->getSortBy(), '.')) {
                // Ordenamiento por campo de relación
                [$relation, $field] = explode('.', $criteria->getSortBy(), 2);
                $relationTable = \Illuminate\Support\Str::plural($relation);
                $query->join(
                    $relationTable, 
                    $relationTable . '.id', 
                    '=', 
                    $query->getModel()->getTable() . ".{$relation}_id"
                )->orderBy($relationTable . '.' . $field, $criteria->getSortDirection());
            } else {
                // Ordenamiento por campo directo
                $query->orderBy($criteria->getSortBy(), $criteria->getSortDirection());
            }
        } else {
            // Usar aplicación estándar de criterios
            $criteria->apply($query);
        }
        
        return $query;
    }

    /**
     * Obtener campos de búsqueda para este repositorio.
     */
    protected function getSearchFields(): array
    {
        return $this->defaultSearchFields;
    }

    /**
     * Establecer campos de búsqueda personalizados.
     */
    public function setSearchFields(array $fields): self
    {
        $this->defaultSearchFields = $fields;
        return $this;
    }

    /**
     * Búsqueda rápida por texto en campos principales.
     */
    public function quickSearch(string $term, array $fields = null): Collection
    {
        $searchFields = $fields ?? $this->getSearchFields();
        
        $query = $this->model->newQuery();
        
        if (!empty($term) && !empty($searchFields)) {
            $query->where(function ($q) use ($term, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$term}%");
                }
            });
        }
        
        return $query->get();
    }

    /**
     * Filtrar por múltiples criterios.
     */
    public function filterBy(array $filters): Collection
    {
        $query = $this->model->newQuery();
        
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }
        
        return $query->get();
    }

    /**
     * Obtener registros ordenados.
     */
    public function getOrderedBy(string $field, string $direction = 'asc'): Collection
    {
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';
        
        return $this->model->newQuery()
            ->orderBy($field, $direction)
            ->get();
    }
}
