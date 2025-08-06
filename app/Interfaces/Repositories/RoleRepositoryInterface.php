<?php

namespace App\Interfaces\Repositories;

use App\Services\Search\SearchCriteria;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Obtener todos los roles excluyendo superadmin.
     * @return Collection
     */
    public function getAllExceptSuperadmin(): Collection;

    /**
     * Buscar roles excluyendo superadmin.
     * @param SearchCriteria $criteria
     * @return Collection
     */
    public function searchExceptSuperadmin(SearchCriteria $criteria): Collection;

    /**
     * Buscar roles con paginación excluyendo superadmin.
     * @param SearchCriteria $criteria
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchWithPaginationExceptSuperadmin(SearchCriteria $criteria, int $perPage = 15): LengthAwarePaginator;
}
