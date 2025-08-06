<?php

namespace App\Services;

use App\Services\Traits\Searchable;
use App\Dtos\Role\CreateRoleDto;
use App\Dtos\Role\UpdateRoleDto;
use App\Repositories\Eloquent\RoleRepository;
use App\Services\Search\SearchCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleService extends BaseService
{
    use Searchable;
    /**
     * @param RoleRepository $roleRepository
     */
    public function __construct(private readonly RoleRepository $roleRepository)
    {
        $this->repository = $this->roleRepository;
        $this->customResourceCollection = \App\Http\Resources\Role\RoleCollection::class;
        $this->customResource = \App\Http\Resources\Role\RoleResource::class;

    }

    /**
     * Sobrescribir método list para excluir superadmin.
     */
    public function list(): ResourceCollection
    {
        $rows = $this->roleRepository->getAllExceptSuperadmin();

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    /**
     * Sobrescribir método paginate para excluir superadmin.
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null): ResourceCollection
    {
        // Crear criterios básicos para paginación
        $criteria = new SearchCriteria();
        $criteria->setSearchFields($this->roleRepository->getPublicSearchFields());

        $rows = $this->roleRepository->searchWithPaginationExceptSuperadmin($criteria, $perPage);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }

        return ResourceCollection::make($rows);
    }

    public function createFromDto(CreateRoleDto $createRoleDto): JsonResource {

        $data = [
            'title' => $createRoleDto->title,
            'name'  => $createRoleDto->name,
            'guard_name' => $createRoleDto->guardName ?? 'web',
        ];

        $roleResource = parent::create($data);
        $role = $roleResource->resource;

        if ($createRoleDto->permisos) {
            $role->givePermissionTo($createRoleDto->permisos);
        }
        return $roleResource;
    }

    public function updateFromDto(UpdateRoleDto $updateRoleDto, int $id): JsonResource {

        $data = [
            'title' => $updateRoleDto->title,
        ];

        $roleResource = parent::update($id, $data);
        $role = $roleResource->resource;

        if ($updateRoleDto->permisos) {
            $role->syncPermissions($updateRoleDto->permisos);
        } else {
            $role->syncPermissions([]);
        }

        return $roleResource;
    }
}
