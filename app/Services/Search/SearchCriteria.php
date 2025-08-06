<?php

namespace App\Services\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Clase para manejar criterios de búsqueda de manera consistente y reutilizable.
 * 
 * Permite aplicar filtros, ordenamiento y búsqueda de texto de manera
 * estandarizada en todos los repositorios.
 */
class SearchCriteria
{
    protected array $filters = [];
    protected array $searchFields = [];
    protected string $searchTerm = '';
    protected string $sortBy = 'id';
    protected string $sortDirection = 'asc';
    protected array $relations = [];

    /**
     * Crear criterios desde una Request HTTP.
     */
    public static function fromRequest(Request $request): self
    {
        $criteria = new self();
        
        // Filtros generales
        $criteria->filters = $request->only([
            'status', 'created_at_from', 
            'created_at_to', 'updated_at_from', 'updated_at_to'
        ]);
        
        // Búsqueda de texto
        $criteria->searchTerm = $request->get('search', '');
        
        // Ordenamiento
        $criteria->sortBy = $request->get('sort_by', 'id');
        $criteria->sortDirection = $request->get('sort_direction', 'asc');
        
        // Relaciones a cargar
        $criteria->relations = $request->get('with', []);
        if (is_string($criteria->relations)) {
            $criteria->relations = explode(',', $criteria->relations);
        }
        
        return $criteria;
    }

    /**
     * Establecer campos donde buscar texto.
     */
    public function setSearchFields(array $fields): self
    {
        $this->searchFields = $fields;
        return $this;
    }

    /**
     * Aplicar criterios a un query builder.
     */
    public function apply(Builder $query): Builder
    {
        // Aplicar filtros
        $this->applyFilters($query);
        
        // Aplicar búsqueda de texto
        $this->applySearch($query);
        
        // Aplicar ordenamiento
        $this->applySort($query);
        
        // Cargar relaciones
        $this->applyRelations($query);
        
        return $query;
    }

    /**
     * Aplicar filtros al query.
     */
    protected function applyFilters(Builder $query): void
    {
        foreach ($this->filters as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            match ($field) {
                'created_at_from' => $query->whereDate('created_at', '>=', $value),
                'created_at_to' => $query->whereDate('created_at', '<=', $value),
                'updated_at_from' => $query->whereDate('updated_at', '>=', $value),
                'updated_at_to' => $query->whereDate('updated_at', '<=', $value),
                'status' => $query->where('estatus', $value),
                default => $query->where($field, $value)
            };
        }
    }

    /**
     * Aplicar búsqueda de texto en los campos especificados.
     */
    protected function applySearch(Builder $query): void
    {
        if (empty($this->searchTerm) || empty($this->searchFields)) {
            return;
        }

        $query->where(function ($q) {
            foreach ($this->searchFields as $field) {
                if (str_contains($field, '.')) {
                    // Campo de relación
                    [$relation, $relationField] = explode('.', $field, 2);
                    $q->orWhereHas($relation, function ($relationQuery) use ($relationField) {
                        $relationQuery->where($relationField, 'LIKE', "%{$this->searchTerm}%");
                    });
                } else {
                    // Campo directo
                    $q->orWhere($field, 'LIKE', "%{$this->searchTerm}%");
                }
            }
        });
    }

    /**
     * Aplicar ordenamiento.
     */
    protected function applySort(Builder $query): void
    {
        $direction = in_array(strtolower($this->sortDirection), ['asc', 'desc']) 
            ? $this->sortDirection 
            : 'asc';

        if (str_contains($this->sortBy, '.')) {
            // Ordenamiento por campo de relación
            [$relation, $field] = explode('.', $this->sortBy, 2);
            $relationTable = \Illuminate\Support\Str::plural($relation);
            $query->join(
                $relationTable, 
                $relationTable . '.id', 
                '=', 
                $query->getModel()->getTable() . ".{$relation}_id"
            )->orderBy($relationTable . '.' . $field, $direction);
        } else {
            // Ordenamiento por campo directo
            $query->orderBy($this->sortBy, $direction);
        }
    }

    /**
     * Cargar relaciones especificadas.
     */
    protected function applyRelations(Builder $query): void
    {
        if (!empty($this->relations)) {
            $query->with($this->relations);
        }
    }

    /**
     * Agregar filtro personalizado.
     */
    public function addFilter(string $field, $value): self
    {
        $this->filters[$field] = $value;
        return $this;
    }

    /**
     * Establecer término de búsqueda.
     */
    public function setSearchTerm(string $term): self
    {
        $this->searchTerm = $term;
        return $this;
    }

    /**
     * Establecer ordenamiento.
     */
    public function setSort(string $field, string $direction = 'asc'): self
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
        return $this;
    }

    /**
     * Establecer relaciones a cargar.
     */
    public function setRelations(array $relations): self
    {
        $this->relations = $relations;
        return $this;
    }

    /**
     * Obtener campos de búsqueda.
     */
    public function getSearchFields(): array
    {
        return $this->searchFields;
    }
}
