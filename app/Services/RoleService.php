<?php

namespace App\Services;

use App\Dtos\Role\CreateRoleDto;
use App\Dtos\Role\UpdateRoleDto;
use App\Repositories\Eloquent\RoleRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleService extends BaseService
{
    /**
     * @param RoleRepository $roleRepository
     */
    public function __construct(private readonly RoleRepository $roleRepository)
    {
        $this->repository = $this->roleRepository;
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
