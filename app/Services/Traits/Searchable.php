<?php

namespace App\Services\Traits;

use App\Services\Search\SearchCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Trait para implementar funcionalidades de búsqueda en servicios.
 * 
 * Proporciona métodos estandarizados para realizar búsquedas con criterios,
 * paginación y filtros de manera consistente en los servicios que lo requieran.
 */
trait Searchable
{
    /**
     * Buscar registros usando criterios de búsqueda.
     * 
     * @param SearchCriteria $criteria Criterios de búsqueda
     * @return ResourceCollection Colección de recursos
     */
    public function search(SearchCriteria $criteria): ResourceCollection
    {
        $rows = $this->repository->search($criteria);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Buscar registros con paginación usando criterios de búsqueda.
     * 
     * @param SearchCriteria $criteria Criterios de búsqueda
     * @param int $perPage Registros por página
     * @return ResourceCollection Colección de recursos paginada
     */
    public function searchWithPagination(SearchCriteria $criteria, int $perPage = 15): ResourceCollection
    {
        $rows = $this->repository->searchWithPagination($criteria, $perPage);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Búsqueda rápida por texto en campos específicos.
     * 
     * @param string $term Término de búsqueda
     * @param array|null $fields Campos donde buscar (opcional)
     * @return ResourceCollection Colección de recursos
     */
    public function quickSearch(string $term, array $fields = null): ResourceCollection
    {
        $rows = $this->repository->quickSearch($term, $fields);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Filtrar registros por múltiples criterios.
     * 
     * @param array $filters Filtros a aplicar
     * @return ResourceCollection Colección de recursos
     */
    public function filterBy(array $filters): ResourceCollection
    {
        $rows = $this->repository->filterBy($filters);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Obtener registros ordenados por un campo específico.
     * 
     * @param string $field Campo por el cual ordenar
     * @param string $direction Dirección del ordenamiento (asc|desc)
     * @return ResourceCollection Colección de recursos
     */
    public function getOrderedBy(string $field, string $direction = 'asc'): ResourceCollection
    {
        $rows = $this->repository->getOrderedBy($field, $direction);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Crear criterios de búsqueda desde una Request HTTP.
     * 
     * @param Request $request Petición HTTP
     * @return SearchCriteria Criterios de búsqueda creados
     */
    protected function createSearchCriteriaFromRequest(Request $request): SearchCriteria
    {
        return SearchCriteria::fromRequest($request);
    }

    /**
     * Buscar con criterios desde Request HTTP.
     * 
     * Método de conveniencia que combina la creación de criterios
     * desde la request y la búsqueda con paginación.
     * 
     * @param Request $request Petición HTTP con parámetros de búsqueda
     * @param int $perPage Registros por página
     * @return ResourceCollection Colección de recursos paginada
     */
    public function searchFromRequest(Request $request, int $perPage = 15): ResourceCollection
    {
        $criteria = $this->createSearchCriteriaFromRequest($request);
        return $this->searchWithPagination($criteria, $perPage);
    }

    /**
     * Búsqueda rápida desde Request HTTP.
     * 
     * @param Request $request Petición HTTP que debe contener 'term'
     * @param array|null $fields Campos donde buscar (opcional)
     * @return ResourceCollection Colección de recursos
     */
    public function quickSearchFromRequest(Request $request, array $fields = null): ResourceCollection
    {
        $term = $request->get('term', '');
        return $this->quickSearch($term, $fields);
    }
}
