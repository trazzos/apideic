<?php

namespace App\Http\Controllers;

use App\Dtos\Role\CreateRoleDto;
use App\Dtos\Role\UpdateRoleDto;
use App\Http\Requests\Role\RolePostRequest;
use App\Http\Requests\Role\RolePutRequest;
use Spatie\Permission\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class RoleController extends BaseController
{

    /**
     * @param RoleService $roleService
     */
    public function __construct(
      private readonly RoleService $roleService
    )
    {

        //$this->middleware('permission:role')->only(['lista']);
        //$this->middleware('permission:role.crear')->only(['store']);
        //$this->middleware('permission:role.editar')->only(['update']);
    }

    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return $this->roleService->list();
    }


    /**
     * @param RolePostRequest $request
     * @return JsonResource
     */
    public function create(RolePostRequest $request): JsonResource
    {
        $createRoleDto = CreateRoleDto::fromRequest($request);
        return $this->roleService->createFromDto($createRoleDto);
    }


    /**
     * @param Role $role
     * @param RolePutRequest $request
     * @return JsonResource
     */
    public function update(Role $role, RolePutRequest $request):JsonResource
    {
        $updateRoleDto = UpdateRoleDto::fromRequest($request);
        return $this->roleService->updateFromDto($updateRoleDto, $role->id);
    }

    /**
     * @param Role $role
     * @return Response
     */
    public function delete(Role $role):Response
    {
        return $this->roleService->delete($role->id);
    }
}
