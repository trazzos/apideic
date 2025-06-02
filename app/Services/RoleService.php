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


    public function crearDesdeDto(CreateRoleDto $createRoleDto): JsonResource {

        $data = [
            'title' => $createRoleDto->tile,
            'name'  => $createRoleDto->name,
            'guard_name' => $createRoleDto->guardName ?? 'web',
        ];

        $roleResource = parent::crear($data);
        $role = $roleResource->resource;

        if ($createRoleDto->permisos) {
            $role->givePermissionTo($createRoleDto->permisos);
        }
        return $roleResource;
    }

    public function actualizarDesdeDto(UpdateRoleDto $updateRoleDto, int $id): JsonResource {

        $data = [
            'title' => $updateRoleDto->tile,
        ];

        $roleResource = parent::actualizar($id, $data);
        $role = $roleResource->resource;

        if ($updateRoleDto->permisos) {
            $role->syncPermissions($updateRoleDto->permisos);
        } else {
            $role->syncPermissions([]);
        }

        return $roleResource;
    }
}
