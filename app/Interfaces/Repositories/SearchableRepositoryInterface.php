<?php

namespace App\Interfaces\Repositories;

use App\Services\Search\SearchCriteria;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interfaz para repositorios que soportan funcionalidades de búsqueda.
 */
interface SearchableRepositoryInterface
{
    /**
     * Buscar registros usando criterios específicos.
     * @param SearchCriteria $criteria
     * @return Collection
     */
    public function search(SearchCriteria $criteria): Collection;

    /**
     * Buscar registros con paginación usando criterios específicos.
     * @param SearchCriteria $criteria
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchWithPagination(SearchCriteria $criteria, int $perPage = 15): LengthAwarePaginator;

    /**
     * Búsqueda rápida por texto en campos principales.
     * @param string $term
     * @param array|null $fields
     * @return Collection
     */
    public function quickSearch(string $term, array $fields = null): Collection;

    /**
     * Filtrar por múltiples criterios.
     * @param array $filters
     * @return Collection
     */
    public function filterBy(array $filters): Collection;

    /**
     * Obtener registros ordenados.
     * @param string $field
     * @param string $direction
     * @return Collection
     */
    public function getOrderedBy(string $field, string $direction = 'asc'): Collection;

    /**
     * Establecer campos de búsqueda personalizados.
     * @param array $fields
     * @return self
     */
    public function setSearchFields(array $fields): self;
}
