<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\RoleRepositoryInterface;
use App\Interfaces\Repositories\SearchableRepositoryInterface;
use App\Repositories\Traits\Searchable;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseEloquentRepository implements RoleRepositoryInterface, SearchableRepositoryInterface
{
    use Searchable;

    public function __construct(Role $model)
    {
        parent::__construct($model);
        
        // Configurar campos de búsqueda específicos para roles
        $this->setSearchFields([
            'title',
            'name'
        ]);
    }

    /**
     * Obtener campos de búsqueda específicos para roles.
     */
    protected function getSearchFields(): array
    {
        return $this->defaultSearchFields;
    }

    /**
     * Obtener campos de búsqueda públicamente.
     */
    public function getPublicSearchFields(): array
    {
        return $this->getSearchFields();
    }

    /**
     * Obtener todos los roles excluyendo superadmin.
     */
    public function getAllExceptSuperadmin(): Collection
    {
        return $this->model->newQuery()
            ->where('name', '!=', 'superadmin')
            ->orderBy('title')
            ->get();
    }

    /**
     * Buscar roles excluyendo superadmin.
     */
    public function searchExceptSuperadmin(\App\Services\Search\SearchCriteria $criteria): Collection
    {
        $query = $this->model->newQuery();
        
        // Excluir superadmin
        $query->where('name', '!=', 'superadmin');
        
        // Establecer campos de búsqueda si no están definidos
        if (empty($criteria->getSearchFields())) {
            $criteria->setSearchFields($this->getSearchFields());
        }
        
        return $criteria->apply($query)->get();
    }

    /**
     * Buscar roles con paginación excluyendo superadmin.
     */
    public function searchWithPaginationExceptSuperadmin(
        \App\Services\Search\SearchCriteria $criteria, 
        int $perPage = 15
    ): \Illuminate\Pagination\LengthAwarePaginator {
        $query = $this->model->newQuery();
        
        // Excluir superadmin
        $query->where('name', '!=', 'superadmin');
        
        // Establecer campos de búsqueda si no están definidos
        if (empty($criteria->getSearchFields())) {
            $criteria->setSearchFields($this->getSearchFields());
        }
        
        return $criteria->apply($query)->paginate($perPage);
    }
}
