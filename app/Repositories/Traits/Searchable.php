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
        
        return $criteria->apply($query);
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
